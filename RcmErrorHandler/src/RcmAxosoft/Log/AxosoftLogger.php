<?php

namespace RcmAxosoft;

use Reliv\AxosoftApi\Model\GenericApiRequest;
use RcmAxosoft\Exception\AxosoftLoggerException;
use RcmErrorHandler\Log\AbstractErrorLogger;
use Reliv\AxosoftApi\V5\Items\ApiRequestList;
use Reliv\AxosoftApi\V5\Items\Defects\ApiRequestCreate;
use Zend\Log\Logger;


/**
 * Class AxosoftLogger
 *
 * AxosoftLogger
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAxosoft
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AxosoftLogger extends AbstractErrorLogger
{
    /**
     * array(
     * 'itemType' => 'defects', // Bug
     * 'projectId' => 10
     * 'enterIssueIfNotStatus' => array(
     *   'closed',
     *   'resolved',
     *  ),
     * ),
     *
     * @var array $options
     */
    protected $options
        = [
            'itemType' => 'defects',
            // Bug
            'projectId' => 10,
            'enterIssueIfNotStatus' => [
                'closed',
                'resolved',
            ],
        ];

    /**
     * @var \Reliv\AxosoftApi\Service\AxosoftApi $api
     */
    protected $api = null;

    /**
     * @param \mixed $api
     * @param array  $options
     */
    public function __construct($api, $options = [])
    {
        $this->api = $api;
        $this->options = array_merge($options, $this->options);
    }

    /**
     * getApi
     *
     * @return \Reliv\AxosoftApi\Service\AxosoftApi|null
     */
    protected function getApi()
    {
        return $this->api;
    }

    /**
     * log
     *
     * @param int   $priority
     * @param mixed $message
     * @param array $extra
     *
     * @return $this
     */
    public function log($priority, $message, $extra = [])
    {
        $summary = $this->prepareSummary($priority, $message);

        $existingItem = $this->getExistingItem($summary);

        if ($existingItem) {
            // Add comment
            $this->addComment($existingItem, $summary, $extra);
        } else {
            // create issue
            $this->createIssue($summary, $extra);
        }

        return $this;
    }

    /**
     * getExistingItem
     *
     * @param $summary
     *
     * @return mixed
     * @throws AxosoftLoggerException
     */
    protected function getExistingItem($summary)
    {
        $api = $this->getApi();

        $request = new ApiRequestList();
        $request->setProjectId($this->getOption('projectId'));
        $request->setSearchString($summary);
        $request->setSearchField('name');
        $request->setPage(1);
        $request->setPageSize(1);

        $response = $api->send($request);

        $data = $response->getData();
        // @todo Error Check

        if (isset($data[0])) {
            return $data[0];
        }

        return null;
    }

    /**
     * addComment
     *
     * @param       $existingItem
     * @param       $summary
     * @param array $extra
     *
     * @return void
     * @throws \Exception
     */
    protected function addComment($existingItem, $summary, $extra = [])
    {
        $updateData = [];

        $updateDate = new \DateTime();
        $updateData['notify_customer'] = false;
        $updateData['item'] = []; //$data[0];

        $updateData['item']['description']
            = $existingItem['description'] . "<br/>- Error occured again: {$summary}"
            . $updateDate->format(\DateTime::W3C);

        //$updateData['item']['notes'] = $data[0]['notes'] . "/n-This has been added on " . $updateDate->format(\DateTime::W3C);
        $updateData['item']['id'] = $existingItem['id'];

        $updateUrl = '/api/v5/' . $existingItem['item_type'] . '/' . $existingItem['id'];

        $request = new GenericApiRequest($updateUrl, 'POST', $updateData);

        $api = $this->getApi();

        $response = $api->send($request);
        // @todo Error Check
    }

    /**
     * createIssue
     *
     * @param       $summary
     * @param array $extra
     *
     * @return void
     * @throws \Exception
     */
    protected function createIssue($summary, $extra = [])
    {
        // Add a new defect
        $request = new ApiRequestCreate();

        $request->setDescription('New Description');
        $request->setName($summary);
        $request->setNotes('New Note');

        $api = $this->getApi();
        $response = $api->send($request);
        // @todo Error Check
    }
}

<?php

namespace RcmIssuu\Controller;

use Rcm\Entity\Site;
use Rcm\Http\Response;
use RcmIssuu\Service\IssuuApi;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class DocumentListController extends AbstractRestfulController
{
    /** @var \RcmIssuu\Service\IssuuApi  */
    protected $api;

    protected $currentSite;

    /**
     * Constructor
     *
     * @param IssuuApi $api         Search API
     * @param Site     $currentSite Current Site for User Check
     */
    public function __construct(IssuuApi $api, Site $currentSite)
    {
        $this->api = $api;
        $this->currentSite = $currentSite;
    }

    /**
     * Return single resource
     *
     * @param  mixed $id Id to retrieve
     *
     * @return mixed
     */
    public function get($id)
    {
        if (!$this->rcmIsSiteAdmin($this->currentSite)) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        $userName = $this->params('username');
        $doc = $this->api->getEmbed($userName, $id);
        return new JsonModel($doc);
    }
}

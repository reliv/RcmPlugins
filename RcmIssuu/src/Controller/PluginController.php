<?php

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace RcmIssuu\Controller;

use Rcm\Plugin\BaseController;
use Rcm\Plugin\PluginInterface;
use RcmIssuu\Service\IssuuApi;
use Zend\View\Model\ViewModel;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class PluginController extends BaseController implements PluginInterface
{
    /** @var \RcmIssuu\Service\IssuuApi  */
    protected $api;

    /**
     * Constructor
     *
     * @param array                      $config Zend Config
     * @param \RcmIssuu\Service\IssuuApi $api    Search API
     */
    public function __construct($config, IssuuApi $api)
    {
        parent::__construct($config);
        $this->api = $api;
    }

    /**
     * Render an instance
     *
     * @param int   $instanceId     Instance ID
     * @param array $instanceConfig Instance Config
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \Exception
     */
    public function renderInstance($instanceId, $instanceConfig)
    {
        $embed = '<div class="issuuError">Please edit this plugin and select a document to show</div>';

        if ($instanceId > 0) {
            $response = $this->api->getEmbed('reliv', 'spring2015ls');
            $embed = $response['html'];
        }

        $view = new ViewModel([
            'instanceId' => $instanceId,
            'instanceConfig' => $instanceConfig,
            'embed' => $embed
        ]);

        $view->setTemplate($this->template);

        return $view;
    }
}

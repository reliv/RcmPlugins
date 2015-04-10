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
class PluginController
    extends BaseController
    implements PluginInterface
{
    /** @var \RcmIssuu\IssuuApi  */
    protected $api;

    public function __construct($config, IssuuApi $api) {
        parent::__construct($config);
        $this->api = $api;
    }

    public function renderInstance($instanceId, $instanceConfig)
    {
        $embed = '<div class="issuuError">Please edit this plugin and select a document to show</div>';

        if ($instanceId > 0) {
            $embed = $this->api->getEmbed('http://issuu.com/reliv/docs/spring2015ls', 700, 0);
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

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
namespace RcmDynamicNavigation\Controller;

use Rcm\Plugin\PluginInterface;
use Rcm\Plugin\BaseController;
use RcmUser\Service\RcmUserService;
use Zend\Authentication\Result;
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

    /**
     * @var \RcmUser\Service\RcmUserService $rcmUserService
     */
    protected $rcmUserService;

    function __construct(
        $config
    ) {
        parent::__construct($config, 'RcmDynamicNavigation');
    }

    public function renderInstance($instanceId, $instanceConfig)
    {
        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig
        );

        $links = [
            0 => [
                0 => [
                    'display' => 'countries',
                    'href' => '#',
                    'target' => null,
                    'class' => 'US country',
                    'backgroundImage' => 'https://content.reliv.com/migrate/content/images/country-flags/United_States_of_America.png',
                    'links' => [
                        0 => [
                            0 => [
                                'display' => 'United States',
                                'href' => 'https://local.reliv.com',
                                'target' => null,
                                'class' => 'US country',
                                'backgroundImage' => 'https://content.reliv.com/migrate/content/images/country-flags/United_States_of_America.png',
                                'links' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];


        $view->setVariable('navigation', $links);

        return $view;
    }
}
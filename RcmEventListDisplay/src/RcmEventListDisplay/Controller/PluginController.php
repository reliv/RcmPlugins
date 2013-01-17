<?php

/**
 * Online App Plugin Controller
 *
 * Main controller for the online app
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   PrivatePlugins\RcmEventListDisplay
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmEventListDisplay\Controller;

/**
 * Online App Plugin Controller
 *
 * Main controller for the online app
 *
 * @category  Reliv
 * @package   PrivatePlugins\RcmEventListDisplay
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class PluginController
    extends \RcmSimpleConfigStorage\Controller\SimpleConfigStorageController
    implements \Rcm\Plugin\PluginInterface
{
    /**
     * @var \RcmEventCalenderCore\Model\Calender $calender
     */
    protected $calender;

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        $template = null,
        $defaultJsonContentFilePath = null,
        \RcmEventCalenderCore\Model\Calender $calender
    ) {
        parent::__construct($entityMgr,$template,$defaultJsonContentFilePath);
        $this->calender = $calender;
    }

    /**
     * Plugin Action - Returns the guest-facing view model for this plugin
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId)
    {
        return $this->renderInstanceFromData(
            $this->configRepo->getInstanceConfig($instanceId)
        );
    }

    function previewAdminAjaxAction(){

        $post = $this->getEvent()->getRequest()->getPost();
        $post=json_decode(json_encode($post));//converts array to object
        return $this->renderInstanceFromData(
            $post
        );
    }

    function renderInstanceFromData($data){

        $events = $this->calender->getEvents($data->categoryId);

        $view = new \Zend\View\Model\ViewModel(
            array(
                'data' => $data,
                'events' => $events
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }

    function renderDefaultInstance(){
        return $this->renderInstanceFromData(
            $this->getDefaultJsonContent()
        );
    }
}


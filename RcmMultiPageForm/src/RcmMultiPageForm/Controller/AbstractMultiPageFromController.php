<?php

/**
 * Abstract controller for multi page forms
 */
namespace RcmResetPassword\Controller;

use Rcm\Plugin\PluginInterface;
use RcmDoctrineJsonPluginStorage\Controller\BasePluginController;
use RcmDoctrineJsonPluginStorage\Service\PluginStorageMgr;
use RcmMuliPageForm\Model\StepModel;

class AbstractMultiPageFromController extends BasePluginController implements
    PluginInterface
{
    /**
     * @var $stepModel StepModel
     * @var $view      \Zend\View\Model\ViewModel
     * @var $post
     * @var $instanceConfig
     */
    protected $stepModel, $view, $post, $instanceConfig;

    public function __construct(
        $config,
        PluginStorageMgr $pluginStorageMgr,
        StepModel $stepModel
    )
    {
        parent::__construct($pluginStorageMgr, $config);
        $this->stepModel = $stepModel;
    }

    /**
     * Plugin Action - Returns the guest-facing view model for this plugin
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderInstance($instanceId)
    {
        $this->instanceConfig = $this->getInstanceConfig($instanceId);

        if ($this->postIsForThisPlugin($this->pluginName)) {
            $this->post = $this->getRequest()->getPost();
        }

        $this->view = parent::renderInstance(
            $instanceId,
            array()
        );

        $this->view->setTemplate(
            $this->pluginNameLowerCaseDash . '/'
            . $this->stepModel->getCurrentStep()
        );

        $this->beforeAction();

        $actionMethod = $this->stepModel->getCurrentStep() . 'Action';
        $this->$actionMethod();

        $this->afterAction();

        return $this->view;
    }

    /**
     * Override this in child if you have code you want to run before the action
     * is called
     */
    public function beforeAction()
    {

    }

    /**
     * Override this in child if you have code you want to run after the action
     * is called
     */
    public function afterAction()
    {

    }
}

<?php

namespace RcmDoctrineJsonPluginStorageTest\Controller;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';
require_once __DIR__ . '/../../../src/RcmDoctrineJsonPluginStorage/Service/PluginStorageMgrInterface.php';
require_once __DIR__ . '/../Mock/PluginStorageMgrMock.php';

use RcmDoctrineJsonPluginStorage\Controller\BasePluginController;
use RcmDoctrineJsonPluginStorage\Service\PluginStorageMgrMock;
use RcmTest\Base\BaseTestCase;
use Zend\Http\PhpEnvironment\Request;

class BasePluginControllerTest extends BaseTestCase
{

    /** @var  \RcmDoctrineJsonPluginStorage\Controller\BasePluginController */
    protected $basePluginController;

    const DEFAULT_HTML = '<h1>hello</h1>';

    /**
     * @var \RcmDoctrineJsonPluginStorage\Service\PluginStorageMgrMock
     */
    protected $pluginStorageMgrMock;

    public function setUp()
    {
        $this->addModule('RcmDoctrineJsonPluginStorage');
        parent::setUp();

        $this->pluginStorageMgrMock = new PluginStorageMgrMock();
        $this->pluginStorageMgrMock->setTestConfig(
            array('html' => self::DEFAULT_HTML)
        );

        $this->basePluginController = new BasePluginController(
            $this->pluginStorageMgrMock,
            array(
                'rcmPlugin' => array(
                    'RcmDoctrineJsonPluginStorage' => array(
                        'defaultInstanceConfig' => array(
                            'html' => self::DEFAULT_HTML
                        )
                    )
                )
            )
        );
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testSetGetRequest()
    {
        $request = new Request();
        $this->basePluginController->setRequest(new Request());
        $this->assertEquals($request, $this->basePluginController->getRequest());
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testRenderInstance()
    {
        $personName = 'bob';
        $viewModel = $this->basePluginController->renderInstance(
            1,
            array('personName' => $personName)
        );
        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $viewModel);
        $this->assertEquals(
            $viewModel->getVariable('personName'),
            $personName
        );
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testRenderDefaultInstance()
    {
        $personName = 'bob';
        $viewModel = $this->basePluginController->renderDefaultInstance(
            -1,
            array('personName' => $personName)
        );
        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $viewModel);
        $this->assertEquals(
            $viewModel->getVariable('personName'),
            $personName
        );
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testGetNewInstanceConfig()
    {
        $instanceConfig = $this->basePluginController->getDefaultInstanceConfig();
        $this->assertEquals($instanceConfig['html'], self::DEFAULT_HTML);
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testSaveInstance()
    {
        $newHtml = '<b>hi</b>';
        $id = 349857;
        $config = array('html' => $newHtml);
        $this->basePluginController->saveInstance(
            $id,
            $config
        );
        $this->assertEquals(
            $id,
            $this->pluginStorageMgrMock->lastSavedInstanceId
        );
        $this->assertEquals(
            $config,
            $this->pluginStorageMgrMock->lastSavedConfig
        );
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testGetInstanceConfig()
    {
        $instanceConfig = $this
            ->basePluginController->getInstanceConfig(1);
        $this->assertEquals($instanceConfig['html'], self::DEFAULT_HTML);

        $defaultInstanceConfig = $this->basePluginController
            ->getInstanceConfig(-1);
        $this->assertEquals($defaultInstanceConfig['html'], self::DEFAULT_HTML);
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */


    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    public function testPostIsForThisPlugin()
    {
        $pluginName = 'RcmJDPluginStorage';
        $_POST['rcmPluginName'] = $pluginName;
        $this->basePluginController->setRequest(new Request());
        $this->assertTrue(
            $this->basePluginController->postIsForThisPlugin($pluginName)
        );

        $_POST['rcmPluginName'] = 'someOtherPlugin';
        $this->basePluginController->setRequest(new Request());
        $this->assertFalse(
            $this->basePluginController->postIsForThisPlugin($pluginName)
        );
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testDeleteInstance()
    {
        $id = 77;
        $this->basePluginController->deleteInstance($id);
        $this->assertEquals(
            $id,
            $this->pluginStorageMgrMock->lastDeletedInstanceId
        );
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    function testCamelToHyphens()
    {
        $this->assertEquals(
            'camel-case',
            $this->basePluginController->camelToHyphens('CamelCase')
        );
        $this->assertEquals(
            'studly-caps',
            $this->basePluginController->camelToHyphens('StudlyCaps')
        );
    }

    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    public function testInstanceConfigAdminAjaxAction()
    {
        $jsonModel = $this->basePluginController
            ->instanceConfigAdminAjaxAction(1);
        $instanceConfig = $jsonModel->getVariable('defaultInstanceConfig');
        $this->assertEquals(
            $instanceConfig['html'],
            self::DEFAULT_HTML
        );
    }
}
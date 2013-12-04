<?php 

namespace RcmDoctrineJsonPluginStorageTest\Controller;

require_once __DIR__ . '/../../../../Rcm/test/Base/PluginTestCase.php';
require_once __DIR__ . '/../Mock/PluginStorageMock.php';

use RcmDoctrineJsonPluginStorage\Controller\BasePluginController;
use RcmDoctrineJsonPluginStorageTest\Mock\PluginStorageMock;
use RcmTest\Base\PluginTestCase;

class BasePluginControllerTest extends PluginTestCase
{

    /** @var  \RcmDoctrineJsonPluginStorage\Controller\BasePluginController */
    protected $basePluginController;

    const DEFAULT_HTML = '<h1>hello</h1>';
    const PERSON_NAME = 'bob';
    const INSTANCE_ID = 7890;

    protected $pluginStorageMock;

    public function setUp()
    {
        $this->addModule('RcmDoctrineJsonPluginStorage');
        parent::setUp();

        $pluginStorageMock = new PluginStorageMock();

        $this->basePluginController = new BasePluginController(
            $pluginStorageMock,
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

//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testSetGetRequest()
//    {
//        $request = new Request();
//        $this->basePluginController->setRequest(new Request());
//        $this->assertEquals($request, $this->basePluginController->getRequest());
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testRenderInstance()
//    {
//        $viewModel = $this->basePluginController->renderInstance(
//            self::INSTANCE_ID,
//            array('personName' => self::PERSON_NAME)
//        );
//        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $viewModel);
//        $this->assertEquals(
//            $viewModel->getVariable('personName'),
//            self::PERSON_NAME
//        );
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testRenderDefaultInstance()
//    {
//        $viewModel = $this->basePluginController->renderDefaultInstance(
//            self::INSTANCE_ID,
//            array('personName' => self::PERSON_NAME)
//        );
//        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $viewModel);
//        $this->assertEquals(
//            $viewModel->getVariable('personName'),
//            self::PERSON_NAME
//        );
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testGetNewInstanceConfig()
//    {
//        $instanceConfig = $this->basePluginController->getDefaultInstanceConfig();
//        $this->assertEquals($instanceConfig['html'], self::DEFAULT_HTML);
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testSaveInstance()
//    {
//        $newHtml = '<b>hi</b>';
//        $this->basePluginController->saveInstance(
//            self::INSTANCE_ID,
//            array('html' => $newHtml)
//        );
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testGetInstanceConfig()
//    {
//        $instanceConfig = $this
//            ->basePluginController->getInstanceConfig(self::INSTANCE_ID);
//        $this->assertEquals($instanceConfig['html'], self::DEFAULT_HTML);
//
//        $defaultInstanceConfig = $this->basePluginController
//            ->getInstanceConfig(-1);
//        $this->assertEquals($defaultInstanceConfig['html'], self::DEFAULT_HTML);
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testMergeConfigArrays()
//    {
//        $merged = $this->basePluginController->mergeConfigArrays(
//            array(
//                'keyedArray' => array('a' => 1, 'c' => 3),
//                'nonKeyedArray' => array('a', 'b', 'c'),
//                'keyedArrayInDefaultOnly' => array('x' => 'y'),
//                'overwrite' => 'original',
//                'nonOverWritten' => 'original'
//            ),
//            array(
//                'keyedArray' => array('b' => 2),
//                'nonKeyedArray' => array('d'),
//                'keyedArrayInChangesOnly' => array('z' => 'x'),
//                'overwrite' => 'new',
//                'inChangesOnly' => 'new'
//            )
//        );
//        $this->assertEquals(
//            $merged,
//            array(
//                'keyedArray' => array('a' => 1, 'b' => 2, 'c' => 3),
//                'nonKeyedArray' => array('d'),
//                'keyedArrayInDefaultOnly' => array('x' => 'y'),
//                'keyedArrayInChangesOnly' => array('z' => 'x'),
//                'overwrite' => 'new',
//                'inChangesOnly' => 'new',
//                'nonOverWritten' => 'original'
//            )
//        );
//
//        $merged = $this->basePluginController->mergeConfigArrays(
//            array('one' => 1),
//            null
//        );
//        $this->assertEquals($merged, array('one' => 1));
//
//        $merged = $this->basePluginController->mergeConfigArrays(
//            null,
//            array('one' => 1)
//        );
//        $this->assertEquals($merged, array('one' => 1));
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    public function testPostIsForThisPlugin()
//    {
//        $pluginName = 'RcmJDPluginStorage';
//        $_POST['rcmPluginName'] = $pluginName;
//        $this->basePluginController->setRequest(new Request());
//        $this->assertTrue(
//            $this->basePluginController->postIsForThisPlugin($pluginName)
//        );
//
//        $_POST['rcmPluginName'] = 'someOtherPlugin';
//        $this->basePluginController->setRequest(new Request());
//        $this->assertFalse(
//            $this->basePluginController->postIsForThisPlugin($pluginName)
//        );
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testDeleteInstance()
//    {
//        $this->basePluginController->deleteInstance(self::INSTANCE_ID);
//    }
//
//    /**
//     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
//     */
//    function testCamelToHyphens()
//    {
//        $this->assertEquals(
//            'camel-case',
//            $this->basePluginController->camelToHyphens('CamelCase')
//        );
//        $this->assertEquals(
//            'studly-caps',
//            $this->basePluginController->camelToHyphens('StudlyCaps')
//        );
//    }
//
    /**
     * @covers\RcmDoctrineJsonPluginStorage\Controller\BasePluginController
     */
    public function testInstanceConfigAdminAjaxAction()
    {
        $jsonModel = $this->basePluginController
            ->instanceConfigAdminAjaxAction(self::INSTANCE_ID);
        $instanceConfig = $jsonModel->getVariable('defaultInstanceConfig');
        $this->assertEquals(
            $instanceConfig['html'],
            self::DEFAULT_HTML
        );
    }
}
<?php

use RcmTest\Base\PluginTestCase;

class PluginControllerTest extends PluginTestCase
{
    /**
     * @var \RcmRotatingImage\Controller\PluginController
     */
    protected $rotatingImage;


    public function setUp()
    {
        parent::setUp();

        $sm = \RcmTest\Base\RcmBootstrap::getServiceManager();
        $this->rotatingImage = $sm->get('RcmRotatingImage');

    }

    private function getDefaultInstanceConfig() {
        return include __DIR__.'/../../../config/defaultInstanceConfig.php';
    }

    public function testInstanceConfig()
    {
        $config = $this->getDefaultInstanceConfig();

        $this->assertFalse(empty($config));
        $this->assertTrue(is_array($config));
    }

    public function testRcmRotatingImageObjectExists()
    {
        $this->assertTrue(is_object($this->rotatingImage));
        $this->assertTrue(
            is_a($this->rotatingImage, '\RcmRotatingImage\Controller\PluginController')
        );
    }

    public function testRenderInstanceReturnsZendViewObject()
    {
        $view = $this->rotatingImage->renderInstance(-1);

        $this->assertTrue(is_object($view));
        $this->assertTrue(is_a($view, '\Zend\View\Model\ViewModel'));
    }

    public function testRenderInstanceReturnsNewInstance()
    {
        $view = $this->rotatingImage->renderInstance(-1);

        $config = $this->getDefaultInstanceConfig();

        $viewInstanceConfig = $view->ic;
        $image = $view->image;

        $this->assertEquals($config, $viewInstanceConfig);
        $this->assertFalse(empty($image));
    }

    public function testGetRandomImageUsingDefaultConfig()
    {
        $config = $this->getDefaultInstanceConfig();

        $image = $this->rotatingImage->getRandomImage($config);
        $this->assertTrue(in_array($image, $config['images']));
    }

    public function testViewRendering()
    {
        $renderer = $this->getRenderer();

        $view = $this->rotatingImage->renderInstance(-1);

        $rendered = $renderer->render($view);

        $this->assertInternalType('integer', strpos($rendered, '<div class="images">'));
    }
}
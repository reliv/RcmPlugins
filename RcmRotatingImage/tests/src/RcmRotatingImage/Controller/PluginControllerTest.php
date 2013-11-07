<?php

namespace RcmRotatingImage\Tests\Src\Controller;

use \Rcm\Tests\Base\DoctrineTestCase;
use \RcmRotatingImage\Tests\Bootstrap;
use RcmDJPluginStorage\Entity\InstanceConfig;

class PluginControllerTest extends DoctrineTestCase
{

    /** @var \RcmRotatingImage\Controller\PluginController */
    public $rcmRotatingImage;

    public function setUp()
    {
        parent::setup();

        $serviceManager = Bootstrap::getServiceManager();

        $this->rcmRotatingImage = $serviceManager->get('RcmRotatingImage');
    }

    private function createInstance()
    {
        $config = include __DIR__.'/../../../../config/defaultInstanceConfig.php';
        $instance = new InstanceConfig();
        $instance->setInstanceId(1);
        $instance->setConfig($config);

        $this->entityManager->persist($instance);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function testRenderInstance()
    {
        $viewModel = $this->rcmRotatingImage->renderInstance(1);

        $this->assertTrue(is_a($viewModel, '\Zend\View\Model\ViewModel'));

        $image = $viewModel->image;

        $this->assertFalse(empty($image));

        $ic = $viewModel->ic;

        $this->assertFalse(empty($ic));

        $this->assertArrayHasKey('learnMore', $ic);
    }

    public function testGetRandomImage()
    {
        $instanceConfig = $this->rcmRotatingImage->getInstanceConfig(1);

        $image = $this->rcmRotatingImage->getRandomImage($instanceConfig);

        $this->assertFalse(empty($image));

        $this->assertArrayHasKey('src', $image);
        $this->assertArrayHasKey('alt', $image);
        $this->assertArrayHasKey('href', $image);

    }
}
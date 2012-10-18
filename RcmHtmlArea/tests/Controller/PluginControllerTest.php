<?php
namespace RcmHtmlArea\Controller;



class PluginControllerTest extends \Rcm\Base\BaseTest
{
    /**
     * @var PluginController
     */
    protected $unit;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @covers Rcm\Controller\PluginControllerInterface
     */
    protected function setUp()
    {
        $this->unit = new PluginController();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers RcmPluginCommon\Controller\BasePluginController::setEm
     * @todo   Implement testSetEntityManager().
     */
    public function testSetEntityManager()
    {
        $em = $this->getEmMock();
        $this->unit->setEm($em);
        $this->assertEquals($em, $this->unit->entityManager);
    }

    /**
     * @covers RcmHtmlArea\Controller\PluginController::pluginAction
     * @covers Rcm\Controller\PluginControllerInterface::pluginAction
     * @covers RcmHtmlArea\Controller\PluginController::readEntity
     */
    public function testPluginAction()
    {
        $html='<h2>Learn to live better</h2>';
        $entity = new \RcmPluginCommon\Entity\JsonContent(
            1, //instanceId
            array('html' =>$html)
        );

        $this->unit->setEm(
            $this->getEmMock(
                array(
                    'RcmPluginCommon\Entity\JsonContent' => array(
                        'findOneByInstanceId' => $entity
                    )
                )
            )
        );

        $view = $this->unit->PluginAction(42);
        $viewVars = $view->getVariables();

        $this->assertEquals(
            $entity->getData()->html,
            $viewVars['content']->html
        );
    }

    /**
     * @covers            RcmHtmlArea\Controller\PluginController::pluginAction
     * @covers            RcmPluginCommon\Exception\PluginDataNotFoundException
     * @covers            RcmPluginCommon\Exception\ExceptionInterface
     * @covers            RcmHtmlArea\Controller\PluginController::readEntity
     * @expectedException RcmPluginCommon\Exception\PluginDataNotFoundException
     */
    public function testPluginActionWithNoInstance()
    {
        $this->unit->setEm(
            $this->getEmMock(
                array(
                    'RcmPluginCommon\Entity\JsonContent' => array(
                        'findOneByInstanceId' => null
                    )
                )
            )
        );

        $this->unit->PluginAction(42);
    }

    /**
     * @covers Rcm\Controller\PluginControllerInterface::saveAction
     * @covers RcmHtmlArea\Controller\PluginController::saveAction
     */
    function testSaveAction()
    {
        $em = $this->getEmMock();

        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $this->unit->setEm($em);

        $this->unit->saveAction(42, '<p>hi</p>');
    }

    /**
     * @covers Rcm\Controller\PluginControllerInterface::deleteAction
     * @covers RcmHtmlArea\Controller\PluginController::deleteAction
     * @covers RcmPluginCommon\Controller\BasePluginController::deleteEntity
     */
    function testDeleteAction()
    {
        $contentEntity = new \RcmPluginCommon\Entity\JsonContent();

        $em = $this->getEmMock(
            array(
                'RcmPluginCommon\Entity\JsonContent' => array(
                    'findOneByInstanceId' => $contentEntity
                )
            )
        );

        $em->expects($this->once())->method('remove')->with($contentEntity);
        $em->expects($this->once())->method('flush');

        $this->unit->setEm($em);

        $this->unit->deleteAction(42);
    }
}

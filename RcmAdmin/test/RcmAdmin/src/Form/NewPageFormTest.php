<?php
/**
 * Unit Test for the NewPageForm
 *
 * This file contains the unit test for NewPageForm
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdminTest\Form;

use RcmAdmin\Form\Element\MainLayout;
use RcmAdmin\Form\NewPageForm;
use Zend\Form\Factory;
use Zend\Form\FormElementManager;
use Zend\Stdlib\Parameters;

require_once __DIR__ . '/../../../autoload.php';

/**
 * Unit Test for NewPageForm
 *
 * Unit Test for NewPageForm
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class NewPageFormTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPageManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockLayoutManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPageValidator;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockTemplateValidate;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockLayoutValidate;

    /** @var \RcmAdmin\Form\NewPageForm */
    protected $form;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $pageList = array(
            '1' => 'template-one',
            '2' => 'template-two',
        );

        $layoutConfig = array(
            'TestHomePage' => array(
                'display' => 'Home Page',
                'file' => 'test-home-page.phtml',
                'screenShot' => 'home-page.png',
            ),
            'TestOtherTemplatePage' => array(
                'display' => 'One Column',
                'file' => 'test-other-page.phtml',
                'screenShot' => 'test-other-page.png',
            ),
            'default' => array(
                'display' => 'Interior Page',
                'file' => 'test-interior-page.phtml',
                'screenShot' => 'interior-page.png',
                'hidden' => true,
            ),
        );

        $this->mockPageValidator = $this
            ->getMockBuilder('\Rcm\Validator\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockTemplateValidate = $this
            ->getMockBuilder('\Rcm\Validator\PageTemplate')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockLayoutValidate = $this
            ->getMockBuilder('\Rcm\Validator\MainLayout')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPageManager = $this
            ->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPageManager->expects($this->any())
            ->method('getPageListByType')
            ->with($this->equalTo('t'))
            ->will($this->returnValue($pageList));

        $this->mockPageManager->expects($this->any())
            ->method('getTemplateValidator')
            ->will($this->returnValue($this->mockTemplateValidate));

        $this->mockPageManager->expects($this->any())
            ->method('getPageValidator')
            ->will($this->returnValue($this->mockPageValidator));

        $this->mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockLayoutManager->expects($this->any())
            ->method('getSiteThemeLayoutsConfig')
            ->will($this->returnValue($layoutConfig));

        $this->mockLayoutManager->expects($this->any())
            ->method('getMainLayoutValidator')
            ->will($this->returnValue($this->mockLayoutValidate));

        /** @var \Rcm\Service\PageManager $mockPageManager */
        $mockPageManager = $this->mockPageManager;

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        $mockLayoutManager = $this->mockLayoutManager;

        $this->form = new NewPageForm(
            $mockPageManager,
            $mockLayoutManager
        );

        $mainLayoutElement = new MainLayout();

        $formManager = new FormElementManager();
        $formManager->setService('mainLayout', $mainLayoutElement);

        $formFactory = new Factory($formManager);

        $this->form->setFormFactory($formFactory);
    }

    /**
     * Test Constructor
     *
     * @return void
     *
     * @covers \RcmAdmin\Form\NewPageForm::__construct
     */
    public function testConstructor()
    {
        $this->assertTrue($this->form instanceof NewPageForm);
    }

    /**
     * Test Init
     *
     * @return void
     *
     * @covers \RcmAdmin\Form\NewPageForm::init
     */
    public function testInit()
    {
        $return = $this->form->init();

        $this->assertNull($return);
    }

    /**
     * Test Is Valid on a new page
     *
     * @return void
     *
     * @covers \RcmAdmin\Form\NewPageForm::isValid
     */
    public function testIsValidForNewPages()
    {
        $post = new Parameters();
        $post->set('page-template', 'blank');
        $post->set('url', 'test-page');
        $post->set('title', 'My test title');
        $post->set('main-layout', 'TestHomePage');

        //Set Validators
        $this->mockLayoutValidate->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->mockPageValidator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->form->init();
        $this->form->setData($post);

        $return = $this->form->isValid();

        $this->assertTrue($return);
    }

    /**
     * Test Is Valid for page created from template
     *
     * @return void
     *
     * @covers \RcmAdmin\Form\NewPageForm::isValid
     */
    public function testIsValidForNewPagesFromTemplate()
    {
        $post = new Parameters();
        $post->set('page-template', 1);
        $post->set('url', 'test-page');
        $post->set('title', 'My test title');
        $post->set('main-layout', 'TestHomePage');

        //Set Validators
        $this->mockLayoutValidate->expects($this->never())
            ->method('isValid');

        $this->mockPageValidator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->mockTemplateValidate->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->form->init();
        $this->form->setData($post);

        $return = $this->form->isValid();

        $this->assertTrue($return);
    }


}
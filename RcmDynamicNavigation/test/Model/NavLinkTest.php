<?php
/**
 * Test for NavLink Data Model
 *
 * Test for NavLink Data Model
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

namespace RcmDynamicNavigation\Test;

use RcmDynamicNavigation\Model\NavLink;

require_once __DIR__ . '/../autoload.php';

/**
 * Test for NavLink Data Model
 *
 * Test for NavLink Data Model
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class NavLinkTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDynamicNavigation\Model\NavLink */
    protected $link;

    /**
     * Setup for all tests
     *
     * @return void
     */
    public function setup()
    {
        $this->link = new NavLink();
    }

    /**
     * Get a config array for tests
     *
     * @param string $display     Display
     * @param string $class       Class
     * @param string $href        Href
     * @param string $target      Target
     * @param string $permissions Permissions
     *
     * @return array
     */
    public function getDataArray(
        $display,
        $class = 'testClass',
        $href = '/test-page',
        $target = '_SELF',
        $permissions = null
    ) {
        return array(
            'class' => $class,
            'href' => $href,
            'target' => $target,
            'display' => $display,
            'permissions' => $permissions
        );
    }

    /**
     * Test the constructor
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('\RcmDynamicNavigation\Model\NavLink', $this->link);
    }

    /**
     * Test the constructor with data
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::__construct
     */
    public function testConstructorWithDefaultInstanceConfig()
    {
        $config = $this->getDataArray('Test Link');
        $link = new NavLink($config);
        $this->assertInstanceOf('\RcmDynamicNavigation\Model\NavLink', $link);
    }

    /**
     * Test Populate
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::populate
     */
    public function testPopulate()
    {
        $mainClass = 'mainLink';
        $mainDisplay = 'Test Link';
        $mainHref = '/main-page';
        $mainTarget = '_BLANK';
        $mainPermissions = array(
            'user1',
            'user2',
            'user3'
        );

        $subClass = 'subLink';
        $subDisplay = 'Test Sub Link';
        $subHref = '/sub-page';
        $subTarget = '_new';
        $subPermissions = array(
            'user4',
            'user5',
            'user6'
        );


        $config = $this->getDataArray(
            $mainDisplay,
            $mainClass,
            $mainHref,
            $mainTarget,
            implode(',', $mainPermissions)
        );

        $extraLink = $this->getDataArray(
            $subDisplay,
            $subClass,
            $subHref,
            $subTarget,
            implode(',', $subPermissions)
        );

        $config['links'] = array($extraLink);

        $this->link->populate($config);

        $this->assertEquals($mainDisplay, $this->link->getDisplay());
        $this->assertEquals($mainClass, $this->link->getClass());
        $this->assertEquals($mainHref, $this->link->getHref());
        $this->assertEquals($mainTarget, $this->link->getTarget());
        $this->assertEquals($mainPermissions, $this->link->getPermissions());

        $links = $this->link->getLinks();

        $this->assertCount(1, $links);

        /** @var NavLink $link */
        $link = array_pop($links);

        $this->assertEquals($subDisplay, $link->getDisplay());
        $this->assertEquals($subClass, $link->getClass());
        $this->assertEquals($subHref, $link->getHref());
        $this->assertEquals($subTarget, $link->getTarget());
        $this->assertEquals($subPermissions, $link->getPermissions());
    }

    /**
     * Test Set And Get Href
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getHref
     * @covers \RcmDynamicNavigation\Model\NavLink::setHref
     */
    public function testSetAndGetHref()
    {
        $href = '/somewhere';
        $this->link->setHref($href);
        $this->assertEquals($href, $this->link->getHref());
    }

    /**
     * Test Add And Get Class
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::addClass
     * @covers \RcmDynamicNavigation\Model\NavLink::getClass
     */
    public function testAddAndGetClass()
    {
        $class = 'SomeClass';
        $this->link->addClass($class);
        $this->assertEquals($class, $this->link->getClass());
    }

    /**
     * Test Set And Get Multiple Classes
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::setClass
     * @covers \RcmDynamicNavigation\Model\NavLink::getClass
     */
    public function testSetAndGetMultipleClasses()
    {
        $class = 'SomeClass AnotherClass YetAnotherClass';
        $this->link->setClass($class);
        $this->assertEquals($class, $this->link->getClass());
    }

    /**
     * Test Add And Get System Class
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::addSystemClass
     * @covers \RcmDynamicNavigation\Model\NavLink::getSystemClass
     */
    public function testAddAndGetSystemClass()
    {
        $class = 'SomeClass';
        $this->link->addSystemClass($class);
        $this->assertEquals($class, $this->link->getSystemClass());
    }

    /**
     * Test Set And Get Multiple System Classes
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::setSystemClass
     * @covers \RcmDynamicNavigation\Model\NavLink::getSystemClass
     */
    public function testSetAndGetMultipleSystemClasses()
    {
        $class = 'SomeClass AnotherClass YetAnotherClass';
        $this->link->setSystemClass($class);
        $this->assertEquals($class, $this->link->getSystemClass());
    }

    /**
     * Test Set And Get Target
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getTarget
     * @covers \RcmDynamicNavigation\Model\NavLink::setTarget
     */
    public function testSetAndGetTarget()
    {
        $target = '_blank';
        $this->link->setTarget($target);
        $this->assertEquals($target, $this->link->getTarget());
    }

    /**
     * Test Set And Get Display
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getDisplay
     * @covers \RcmDynamicNavigation\Model\NavLink::setDisplay
     */
    public function testSetAndGetDisplay()
    {
        $display = 'Some words go here';
        $this->link->setDisplay($display);
        $this->assertEquals($display, $this->link->getDisplay());
    }

    /**
     * Test Add And Get Permission
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getPermissions
     * @covers \RcmDynamicNavigation\Model\NavLink::addPermission
     */
    public function testAddAndGetPermission()
    {
        $permission = 'user1';
        $this->link->addPermission($permission);

        $result = $this->link->getPermissions();

        $this->assertCount(1, $result);
        $this->assertContains($permission, $result);
    }

    /**
     * Test Set And Get Permissions
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getPermissions
     * @covers \RcmDynamicNavigation\Model\NavLink::setPermissions
     */
    public function testSetAndGetPermissions()
    {
        $permissions = array(
            'user1',
            'user2',
            'user3'
        );

        $this->link->setPermissions($permissions);

        $result = $this->link->getPermissions();

        $this->assertCount(3, $result);
        $this->assertEquals($permissions, $result);
    }

    /**
     * Test Set And Get Permissions From Csv
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getPermissions
     * @covers \RcmDynamicNavigation\Model\NavLink::setPermissions
     */
    public function testSetAndGetPermissionsFromCsv()
    {
        $permissions = array(
            'user1',
            'user2',
            'user3'
        );

        $this->link->setPermissions(implode(',', $permissions));

        $result = $this->link->getPermissions();

        $this->assertCount(3, $result);
        $this->assertEquals($permissions, $result);
    }

    /**
     * Test Add And Get Links
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::addLink
     * @covers \RcmDynamicNavigation\Model\NavLink::getLinks
     */
    public function testAddAndGetLinks()
    {
        $display = 'SubLink';
        $linkToAdd = new NavLink();
        $linkToAdd->setDisplay($display);
        $this->link->addLink($linkToAdd);

        $result = $this->link->getLinks();
        $this->assertCount(1, $result);

        /** @var NavLink $resultSublink */
        $resultSublink = array_pop($result);

        $this->assertInstanceOf('\RcmDynamicNavigation\Model\NavLink', $resultSublink);

        $this->assertEquals($display, $resultSublink->getDisplay());
    }

    /**
     * Test Add And Get Links From Data Array
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::addLink
     * @covers \RcmDynamicNavigation\Model\NavLink::getLinks
     */
    public function testAddAndGetLinksFromDataArray()
    {
        $display = 'SubLink';
        $sublinkConfig = $this->getDataArray($display);
        $this->link->addLink($sublinkConfig);

        $result = $this->link->getLinks();
        $this->assertCount(1, $result);

        /** @var NavLink $resultSublink */
        $resultSublink = array_pop($result);

        $this->assertInstanceOf('\RcmDynamicNavigation\Model\NavLink', $resultSublink);

        $this->assertEquals($display, $resultSublink->getDisplay());
    }

    /**
     * Test Set And Get Links
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::setLinks
     * @covers \RcmDynamicNavigation\Model\NavLink::getLinks
     */
    public function testSetAndGetLinks()
    {
        $linkOne = new NavLink();
        $linkOne->setDisplay('Display 1');
        $linkTwo = new NavLink();
        $linkTwo->setDisplay('Display 2');
        $linkThree = new NavLink();
        $linkThree->setDisplay('Display 3');

        $linkArray = array($linkOne, $linkTwo, $linkThree);

        $this->link->setLinks($linkArray);

        $result = $this->link->getLinks();

        $this->assertEquals($linkArray, $result);
    }

    /**
     * Test Is Login Link
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::isLoginLink
     */
    public function testIsLoginLink()
    {
        $class = 'someClass someAdditionalClass ';
        $class .= NavLink::LOGIN_CLASS;

        $this->link->setClass($class);

        $this->assertTrue($this->link->isLoginLink());
    }

    /**
     * Test Is Login Link False
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::isLoginLink
     */
    public function testIsLoginLinkFalse()
    {
        $class = 'someClass someAdditionalClass ';
        $this->link->setClass($class);
        $this->assertFalse($this->link->isLoginLink());
    }

    /**
     * Test Is Logout Link
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::isLogoutLink
     */
    public function testIsLogoutLink()
    {
        $class = 'someClass someAdditionalClass ';
        $class .= NavLink::LOGOUT_CLASS;

        $this->link->setClass($class);

        $this->assertTrue($this->link->isLogoutLink());
    }

    /**
     * Test Is Logout Link False
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::isLogoutLink
     */
    public function testIsLogoutLinkFalse()
    {
        $class = 'someClass someAdditionalClass ';
        $this->link->setClass($class);
        $this->assertFalse($this->link->isLogoutLink());
    }

    /**
     * Test Has Links
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::hasLinks
     */
    public function testHasLinks()
    {
        $linkOne = new NavLink();
        $linkOne->setDisplay('Display 1');
        $linkTwo = new NavLink();
        $linkTwo->setDisplay('Display 2');
        $linkThree = new NavLink();
        $linkThree->setDisplay('Display 3');

        $linkArray = array($linkOne, $linkTwo, $linkThree);

        $this->link->setLinks($linkArray);

        $this->assertTrue($this->link->hasLinks());
    }

    /**
     * Test Has Links False
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::hasLinks
     */
    public function testHasLinksFalse()
    {
        $this->assertFalse($this->link->hasLinks());
    }
}

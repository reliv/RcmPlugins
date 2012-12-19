<?php

/**
 * Content Entity Test
 *
 * This is a test for the Doctorine 2 entity for Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmRotatingImage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

require_once __DIR__ . '/../../src/RcmRotatingImage/Entity/Image.php';

/**
/**
 * Content Entity Test
 *
 * This is a test for the Doctorine 2 entity for Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmRotatingImage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

class RcmRotatingImageContentTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \RcmRotatingImage\Entity\Content Content entity we are testing
     */
    private $unit;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->unit = new \RcmRotatingImage\Entity\Image();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->unit = null;
        parent::tearDown();
    }


    /**
     * Tests getting and setting instanceId.
     *
     * @return null
     */
    function testGetSetInstanceId()
    {
        $val = '42';
        $this->unit->setInstanceId($val);
        $this->assertEquals($val, $this->unit->getInstanceId());
    }

    /**
     * Tests getting and setting src.
     *
     * @return null
     */
    function testGetSetSrc()
    {
        $val = 'http://www.google.com/images/srpr/logo3w.png';
        $this->unit->setSrc($val);
        $this->assertEquals($val, $this->unit->getSrc());
    }

    /**
     * Tests getting and setting alt.
     *
     * @return null
     */
    function testGetSetAlt()
    {
        $val = 'Funny man';
        $this->unit->setAlt($val);
        $this->assertEquals($val, $this->unit->getAlt());
    }

    /**
     * Tests getting and setting href.
     *
     * @return null
     */
    function testGetSetHref()
    {
        $val = 'http:\\google.com';
        $this->unit->setHref($val);
        $this->assertEquals($val, $this->unit->getHref());
    }

}
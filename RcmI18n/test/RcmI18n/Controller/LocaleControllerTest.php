<?php
 /**
 * LocaleControllerTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmI18nTest\Controller;

use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Entity\Site;

require_once __DIR__ . '/../../autoload.php';


/**
 * LocaleController Test
 *
 * Controller Test for Autoship Order List
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmShoppingCartPlugins
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class LocaleControllerTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {

        $sites = $this->getMockBuilder('\Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->setMethods(array('getSiteId','getCountry','getLanguage'))
            ->getMock();
        $sites->expects($this->any())
            ->method('getSiteId')
            ->will($this->returnValue(5));
        $sites->expects($this->any())
            ->method('getCountry')
            ->will($this->returnValue(new Country()));
        $sites->expects($this->any())
              ->method('getLanguage')
              ->will($this->returnValue(new Language()));
        $em = $this->getMockBuilder(
            '\Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $country = $this->getMockBuilder('\Rcm\Entity\Country')
            ->disableOriginalConstructor()
            ->setMethods(array('getIso2'))
            ->getMock();
        $country->expects($this->any())
            ->method('getIso2')
            ->will($this->returnValue('AO'));
        $language = $this->getMockBuilder('\Rcm\Entity\Language')
            ->disableOriginalConstructor()
            ->setMethods(array('getIso6391'))
            ->getMock();
        $language->expects($this->any())
            ->method('getIso6391')
            ->will($this->returnValue('en'));


        $em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue(new \ArrayIterator([$sites])));
        $this->assertEquals(5,$sites->getSiteId());
        $this->assertEquals(new Country(), $sites->getCountry());
        $this->assertEquals(new Language() ,$sites->getLanguage());
        $this->assertEquals('AO', $country->getIso2());
        $this->assertEquals('en', $language->getIso6391());



    }

}
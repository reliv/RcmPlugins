<?php
 /**
 * PluginControllerFactoryTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmRecommendedProducts\test\RcmRecommendedProducts\Factory
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmRecommendedProductsTest\Factory;

use RcmRecommendedProducts\Controller\PluginController;
use RcmRecommendedProducts\Factory\PluginControllerFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;

require_once __DIR__ . '/../../autoload.php';


 /**
 * PluginControllerFactoryTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmRecommendedProducts\test\RcmRecommendedProducts\Factory
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class PluginControllerFactoryTest extends \PHPUnit_Framework_TestCase {

    public function testCreateService() {

        $config = [
            'rcmPlugin' => [
            'RcmRecommendedProducts' => [
                'type' => 'Content Templates',
                'display' => 'Recommended Products',
                'tooltip' => '',
                'icon' => '',
                'editJs' => '/modules/rcm-recommended-products/rcm-recommended-products-edit.js'],
            ],
        ];

        $mockProductModel = $this
            ->getMockBuilder('\App\Model\ProductModel')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();

        $serviceManager->setService(
            'rcmShoppingCartProductModel',
            $mockProductModel
        );

        $serviceManager->setService(
            'config',
            $config
        );

        $factory = new PluginControllerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof PluginController);
    }

}
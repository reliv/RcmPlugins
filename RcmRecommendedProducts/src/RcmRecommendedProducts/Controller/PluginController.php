<?php

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace RcmRecommendedProducts\Controller;

use Rcm\Plugin\PluginInterface;
use Rcm\Plugin\BaseController;
use RcmShoppingCart\Entity\Sku;
use RcmShoppingCart\Model\OrderMgr;
use RcmShoppingCart\Model\ProductModel;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class PluginController
    extends BaseController
    implements PluginInterface
{
    /**
     * __construct
     *
     * @param null $config config
     */
    public function __construct(
        $config,
        ProductModel $productModel,
        OrderMgr $orderMgr

    ) {
        parent::__construct($config);
        $this->productModel = $productModel;
        $this->orderMgr = $orderMgr;
    }

    public function renderInstance($instanceId, $instanceConfig)
    {
        $productId = (int)$instanceConfig['productId'];
        $product = $this->productModel->getProductById($productId);
        $skuNumber = (int)$instanceConfig['skuNumber'];
        $sku = $product->getSkuByNumber($skuNumber);

        $productName = $product->getName();
        $mainImage = $sku->getMainImage()->getImageSrc();

            $view = parent::renderInstance(
                $instanceId,
                $instanceConfig
            );

            $view->setVariables(
                array(
                    'prodName' => $productName,
                    'mainImage' => $mainImage
                )
            );

        return $view;
    }
}
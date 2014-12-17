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

use Aws\CloudFront\Exception\Exception;
use Rcm\Plugin\PluginInterface;
use Rcm\Plugin\BaseController;
use App\Entity\Sku;
use App\Model\OrderMgr;
use App\Model\ProductModel;

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
        ProductModel $productModel

    ) {
        parent::__construct($config);
        $this->productModel = $productModel;
    }

    public function renderInstance($instanceId, $instanceConfig)
    {
        return $this->getRecommendedProductsList($instanceId, $instanceConfig);
    }

    public function getRecommendedProductsList($instanceId, $instanceConfig)
    {
        if(!empty($instanceConfig['productId'])) {
            $productId = (int)$instanceConfig['productId'];
        } else {
            throw new Exception('There is no product Id in default instance config');
        }
        $product = $this->productModel->getProductById($productId);
        $productDetailedPage = $product->getDetailedPage();
        $prodUrl = '/p/' . $productDetailedPage;
        $sku = $product->getDefaultSku();

        $productName = $product->getName();
        $mainImage = $sku->getMainImage()->getImageSrc();

        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig
        );

        $view->setVariables(
            [
                'prodName' => $productName,
                'mainImage' => $mainImage,
                'prodUrl' => $prodUrl
            ]
        );
        $view->setTerminal(true);
        return $view;
    }

    public function refreshProductListAction()
    {
        $prodId = $this->getEvent()->getRouteMatch()->getParam('productId');

        $instanceConfig = ['productId' => $prodId];

        $view = $this->getRecommendedProductsList(0, $instanceConfig);

        return $view;
    }
}
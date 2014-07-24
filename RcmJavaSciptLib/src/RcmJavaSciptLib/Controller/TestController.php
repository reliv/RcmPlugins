<?php

namespace RcmJavaSciptLib\Controller;

use
    Zend\Http\Response;
use
    Zend\Mvc\Controller\AbstractActionController;
use
    Zend\View\Model\ViewModel;

/**
 * Class TestController
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmTinyMce\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class TestController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->getTestView();
    }

    /**
     * htmlEditorExampleAction
     *
     * @return array
     */
    public function htmlEditorExampleAction()
    {
        return $this->getTestView();
    }

    /**
     * rcmCoreExampleAction
     *
     * @return array
     */
    public function rcmCoreExampleAction()
    {
        return $this->getTestView();
    }

    /**
     * getTestView
     *
     * @return array
     */
    public function getTestView()
    {

        $template = $this->params()->fromRoute(
            'template',
            'index.phtml'
        );

        /** @var ViewModel $viewModel */
        $viewModel = new ViewModel();
        $templateRoot = realpath(
            __DIR__ . '/../../../view/rcm-java-scipt-lib/test/'
        );
        $templatePath = $templateRoot . '/' . $template . '.phtml';
        var_dump($templatePath);
        $viewModel->setTemplate($templatePath);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $testType = $request->getQuery(
            'testType',
            'default'
        );
        $baseUrl = $this->getRequest()->getServer('HTTP_HOST');

        $viewModel->setVariables(
            array(
                'testType' => $testType,
                'baseUrl' => $baseUrl,
            )
        );

        return $viewModel;
    }
}

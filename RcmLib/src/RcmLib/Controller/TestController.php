<?php

namespace RcmLib\Controller;

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
        $templatePath = '/rcm-lib/test/' . $template . '.phtml';

        $viewModel->setTemplate($templatePath);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $testType = $request->getQuery(
            'testtype',
            'default'
        );
        $terminate = $request->getQuery(
            'terminate',
            false
        );

        if($terminate){
            $viewModel->setTerminal(true);
        }

        $baseUrl = $this->getRequest()->getServer('HTTP_HOST');

        $viewModel->setVariables(
            [
                'testType' => $testType,
                'baseUrl' => $baseUrl,
            ]
        );

        return $viewModel;
    }
}

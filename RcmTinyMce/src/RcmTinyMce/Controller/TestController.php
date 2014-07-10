<?php

namespace RcmTinyMce\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

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
    /**
     * indexAction
     *
     * @return array
     */
    public function indexAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $testType = $request->getQuery('testType', 'default');
        $baseUrl = $this->getRequest()->getServer('HTTP_HOST');
        return array(
            'testType' => $testType,
            'baseUrl' => $baseUrl,
        );
    }
}

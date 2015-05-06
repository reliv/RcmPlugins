<?php


namespace RcmErrorHandler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


/**
 * Class TestController
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class TestController extends AbstractActionController
{

    /**
     * errorAction
     *
     * @return ViewModel
     */
    public function errorAction()
    {
        trigger_error('RcmErrorHandler TEST ERROR', E_USER_ERROR);
    }

    /**
     * exceptionAction
     *
     * @return void
     * @throws \Exception
     */
    public function exceptionAction()
    {
        throw new \Exception('RcmErrorHandler TEST EXCEPTION');
    }

}
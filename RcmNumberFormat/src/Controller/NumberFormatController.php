<?php

namespace RcmNumberFormat\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Module Config For ZF2
 *
 * PHP version 5.4
 *
 * LICENSE: New BSD License
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2013 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
class NumberFormatController extends AbstractActionController
{
    /**
     * Returns formatted number view model
     *
     * @return JsonModel
     */
    public function numberAction()
    {
        $value = $this->getRequestValue();
        if ($value !== null) {
            return $this->getView(sprintf('%.2f', $this->getRequestValue()));
        }
        return $this->getNonNumericView();
    }

    /**
     * Returns formatted currency number view model
     *
     * @return JsonModel
     */
    public function currencyAction()
    {
        $value = $this->getRequestValue();
        if ($value !== null) {
            return $this->getView(
                money_format('%.2n', $this->getRequestValue())
            );
        }
        return $this->getNonNumericView();
    }

    public function getView($formattedResult)
    {
        return new JsonModel(
            [
                'result' => $formattedResult
            ]
        );
    }

    public function getNonNumericView()
    {
        $this->getResponse()->setStatusCode(400); //Bad Request
        return new JsonModel(
            [
                'error' => 'Value to format is not numeric.'
            ]
        );
    }

    /**
     * returns the formatted request value
     *
     * We cannot user FILTER_VALIDATE_FLOAT here because it stops working for
     * value 1.99 in germany where the filter_var would expect 1,99
     *
     * @return float
     */
    public function getRequestValue()
    {
        if (is_numeric($this->params()->fromRoute('value'))) {
            return $this->params()->fromRoute('value');
        }
        return null;
    }
}
<?php

namespace RcmNumberFormat\Controller;

use RcmNumberFormat\Model\CurrencyFormatter;
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
 * @package   RcmNumberFormat
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2013 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

class NumberFormatController extends AbstractActionController
{

    protected $currencyFormatter;

    /**
     * @param CurrencyFormatter $currencyFormatter
     */
    public function __construct(CurrencyFormatter $currencyFormatter)
    {
        $this->currencyFormatter = $currencyFormatter;
    }

    /**
     * Returns formatted number view model
     * @return JsonModel
     */
    public function numberAction()
    {
        return new JsonModel(
            array(
                'result' => (string)number_format($this->getRequestNumber(), 2)
            )
        );
    }

    /**
     * Returns formatted currency number view model
     * @return JsonModel
     */
    public function currencyAction()
    {
        return new JsonModel(
            array(
                'result' => $this->currencyFormatter->formatCurrency(
                        $this->getRequestNumber()
                    )
            )
        );
    }

    /**
     * returns the formatted request value
     * @return string
     */
    public function getRequestNumber()
    {
        return filter_var(
            $this->getEvent()->getRouteMatch()->getParam('number'),
            FILTER_SANITIZE_NUMBER_FLOAT
        );
    }
}
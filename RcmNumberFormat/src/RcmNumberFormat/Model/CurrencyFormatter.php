<?php

namespace RcmNumberFormat\Model;

use Zend\I18n\View\Helper\NumberFormat;

/**
 * Currency Formatter
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

class CurrencyFormatter
{
    protected $currencySymbol;

    protected $numberFormatter;

    /**
     * @param $currencySymbol string currency symbol or code
     */
    public function __construct($currencySymbol)
    {
        $this->numberFormatter = new NumberFormat();
        $this->currencySymbol = $currencySymbol;
    }

    /**
     * returns formatted currency with the currency symbol
     * @param $number float number to format
     * @return string
     */
    public function formatCurrency($number)
    {
        return $this->currencySymbol . $this->numberFormatter->__invoke($number);
    }
} 
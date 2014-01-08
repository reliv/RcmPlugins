<?php

namespace RcmNumberFormat\Model;

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

class CurrencyFormatter extends \NumberFormatter
{
    protected $currencySymbol;

    protected $numberFormatter;


    public function __construct(
        $currencySymbol, \NumberFormatter $numberFormatter
    )
    {
        $this->currencySymbol = $currencySymbol;
        $this->numberFormatter = $numberFormatter;
    }

    /**
     * Returns formatted number with currency symbol
     * @param number $number
     * @param null $strictStandardsJunk
     * @return string
     */
    public function format($number, $strictStandardsJunk = null)
    {
        return $this->currencySymbol . $this->numberFormatter->format($number);
    }
} 
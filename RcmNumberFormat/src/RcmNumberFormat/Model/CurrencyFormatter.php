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

class CurrencyFormatter
{
    protected $currencySymbol;

    /**
     * @param $currencySymbol string currency symbol or code
     */
    public function __construct($currencySymbol)
    {
        $this->currencySymbol = $currencySymbol;
    }

    /**
     * returns formatted currency with the currency symbol
     * @param $number float number to format
     * @return string
     */
    public function formatCurrency($number)
    {
        return $this->currencySymbol . self::numberFormatLocale($number,2);
    }

    public static function numberFormatLocale($number, $decimals){
        $locale = localeconv();
        return number_format(
            $number,
            $decimals,
            $locale['decimal_point'],
            $locale['thousands_sep']
        );
    }
} 
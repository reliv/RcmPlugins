<?php
/**
 * Add Layout Container Helper.
 *
 * Contains the view helper to add a layout container to a page layout
 *
 * PHP version 5.3
 *
 * LICENSE: New BSD License
 *
 * @category  Reliv
 * @package   Common\View\Helper
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

namespace  RcmNumberFormat\View\Helper;
use RcmNumberFormat\Model\CurrencyFormatter;
use \Zend\View\Helper\AbstractHelper;

/**
 * View Helper two format currencies using the currencySymbol in the rcmSite
 * service
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

class CurrencyFormat extends AbstractHelper
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
     * View helper func to format currencies
     * @param $number float the number to format
     * @return string
     */
    public function __invoke($number)
    {
        return $this->currencyFormatter->formatCurrency($number);
    }
}
<?php

namespace RcmNumberFormatTest\View\Helper;

use RcmNumberFormat\Controller\NumberFormatController;
use RcmNumberFormat\Model\CurrencyFormatter;
use RcmNumberFormat\View\Helper\CurrencyFormat;
use RcmTest\Base\BaseTestCase;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

require_once __DIR__ . '/../../../../../../Rcm/test/Base/BaseTestCase.php';

class CurrencyFormatTest extends BaseTestCase
{
    /**
     * @var NumberFormatController
     */
    protected $unit;

    /**
     * @var CurrencyFormatter
     */
    protected $formatter;

    const CURRENCY_SYMBOL = 'EUR';
    const NUMBER = 1000999.88;

    public function setUp()
    {
        $this->addModule('RcmNumberFormat');
        parent::setUp();
        $this->formatter = new CurrencyFormatter(self::CURRENCY_SYMBOL);
        $this->unit = new CurrencyFormat($this->formatter);
    }

    /**
     * @covers \RcmNumberFormat\View\Model\CurrencyFormat
     */
    public function testInvoke()
    {
        $this->assertEquals(
            $this->unit->__invoke(self::NUMBER),
            $this->formatter->format(self::NUMBER)
        );
    }
}
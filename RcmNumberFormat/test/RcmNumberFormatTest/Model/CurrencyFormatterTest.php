<?php


namespace RcmNumberFormatTest\Entity;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';

use RcmNumberFormat\Model\CurrencyFormatter;
use RcmTest\Base\BaseTestCase;

class CurrencyFormatterTest extends BaseTestCase
{
    /**
     * @var CurrencyFormatter
     */
    protected $unit;

    const CURRENCY_SYMBOL = 'EUR';

    public function setUp()
    {
        $this->addModule('RcmNumberFormat');
        parent::setUp();
        $this->unit = new CurrencyFormatter('EUR');
    }

    /**
     * @covers \RcmNumberFormat\Model\CurrencyFormatter
     */
    public function testFormatCurrency()
    {
        $amount = 1000999.88;
        $this->assertEquals(
            $this->unit->formatCurrency($amount),
            self::CURRENCY_SYMBOL . number_format($amount, 2)
        );
    }
} 
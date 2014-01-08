<?php

namespace RcmNumberFormatTest\Controller;

use RcmNumberFormat\Controller\NumberFormatController;
use RcmNumberFormat\Model\CurrencyFormatter;
use RcmTest\Base\BaseTestCase;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';

class NumberFormatControllerTest extends BaseTestCase
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
        $this->unit = new NumberFormatController($this->formatter);
        $event = new MvcEvent();
        $event->setRouteMatch(
            new RouteMatch(
                array('number'=> self::NUMBER)
            )
        );
        $this->unit->setEvent($event);
    }

    /**
     * @covers \RcmNumberFormat\Model\NumberFormatController
     */
    public function testNumberAction()
    {
        $response = $this->unit->numberAction()->getVariables();
        $this->assertEquals(
            $response['result'],
            number_format(self::NUMBER, 2)
        );
    }

    /**
     * @covers \RcmNumberFormat\Model\NumberFormatController
     */
    public function testCurrencyAction()
    {
        $response = $this->unit->currencyAction()->getVariables();
        $this->assertEquals(
            $this->formatter->format(self::NUMBER),
            $response['result']
        );
    }
}
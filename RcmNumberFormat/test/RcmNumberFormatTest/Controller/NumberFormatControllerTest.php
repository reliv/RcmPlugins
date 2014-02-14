<?php

namespace RcmNumberFormatTest\Controller;

use RcmNumberFormat\Controller\NumberFormatController;
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

    const VALUE = 1.99;

    public function setUp()
    {
        $this->addModule('RcmNumberFormat');
        parent::setUp();
        $this->unit = new NumberFormatController();
        $this->setRouteValues(array('value' => self::VALUE));
    }

    public function setRouteValues(array $routeValues)
    {
        $event = new MvcEvent();
        $event->setRouteMatch(new RouteMatch($routeValues));
        $this->unit->setEvent($event);
    }

    /**
     * @covers \RcmNumberFormat\Controller\NumberFormatController
     */
    public function testNumberActionUs()
    {
        setlocale(LC_ALL, 'en_US.UTF-8');
        $this->assertEquals(
            '1.99',
            $this->unit->numberAction()->getVariables()['result']
        );
    }

    /**
     * @covers \RcmNumberFormat\Controller\NumberFormatController
     */
    public function testNumberActionDe()
    {
        setlocale(LC_ALL, 'de_DE.UTF-8');
        $this->assertEquals(
            '1,99',
            $this->unit->numberAction()->getVariables()['result']
        );
    }

    /**
     * @covers \RcmNumberFormat\Controller\NumberFormatController
     */
    public function testCurrencyActionUs()
    {
        setlocale(LC_ALL, 'en_US.UTF-8');
        $this->assertEquals(
            '$1.99',
            $this->unit->currencyAction()->getVariables()['result']
        );
    }

    /**
     * @covers \RcmNumberFormat\Controller\NumberFormatController
     */
    public function testCurrencyActionUsDe()
    {
        setlocale(LC_ALL, 'de_DE.UTF-8');

        $result = $this->unit->currencyAction()->getVariables()['result'];

        $this->assertTrue('Eu1,99' == $result || '1,99 €' == $result);
    }
}
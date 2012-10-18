<?php
namespace RcmSocialButtons\Module;

class ModuleTest extends \Rcm\Base\BaseTest
{
    /**
     * @var Module
     */
    protected $unit;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->unit = new \RcmSocialButtons\Module();
        $GLOBALS['em'] = $this->getEmMock();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($GLOBALS['em']);
    }

    /**
     * @covers RcmSocialButtons\Module
     */
    public function testgetServiceConfig()
    {
        $config = $this->unit->getServiceConfig();
        $sm = $this->getServiceManagerMock($config['factories']);
        foreach (array_keys($config['factories']) as $serviceName) {
            $this->assertTrue(
                get_class($sm->get($serviceName)) == $serviceName
            );
        }
    }
}

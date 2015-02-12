<?php

namespace RcmErrorHandler\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class ApiJsErrorLogger
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiJsErrorLoggerController extends AbstractRestfulController
{
    /**
     * getLoggerConfig
     *
     * @return array
     */
    protected function getLoggerConfig()
    {
        $serviceLocator = $this->getServiceLocator();
        $config = $serviceLocator->get('\RcmErrorHandler\Config');

        $configs = $config->get('jsLoggers', []);

        if(empty($configs)){
            return [];
        }

        return $configs;
    }

    /**
     * log
     *
     * @param string $message
     * @param array  $extra
     *
     * @return void
     */
    protected function log($message, $extra = [])
    {
        $loggerConfig = $this->getLoggerConfig();

        $serviceLocator = $this->getServiceLocator();

        foreach($loggerConfig as $serviceName){

            if($serviceLocator->has($serviceName)){
                /** @var \Zend\Log\LoggerInterface $logger */
                $logger = $serviceLocator->get($serviceName);
                $logger->err($message, $extra);
            }
        }
    }

    /**
     * create
     *
     * @param mixed $data
     *  $data = [
     *  'message' => 'some message',
     *  'file' => '/some/url',
     *  'line' => 123,
     *  'description' => 'Some Description',
     *  'trace' => ''
     *  ];
     *
     * @return void
     */
    public function create($data)
    {
        $this->log($data['message'], $data);

        $view = new JsonModel([]);

        return $view;
    }
}
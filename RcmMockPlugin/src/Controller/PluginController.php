<?php

namespace RcmMockPlugin\Controller;

use Rcm\Plugin\BaseController;
use Rcm\Plugin\PluginInterface;
use RcmMockPlugin\Exception\RuntimeException;
use Zend\Cache\Storage\StorageInterface;
use Zend\Stdlib\RequestInterface;
use Zend\View\Model\ViewModel;

class PluginController extends BaseController implements PluginInterface
{
    protected $request;
    protected $cache;
    protected $activeCache;

    /**
     * @param array            $config
     * @param StorageInterface $cache
     */
    public function __construct(
        $config,
        StorageInterface $cache
    ) {
        $this->cache = $cache;

        if ($this->cache->hasItem('mockPluginData')) {
            $this->activeCache = $this->cache->getItem('mockPluginData');

            return;
        }

        $this->activeCache = [
            -1 => ['instanceData' => '<p>This is a instance id -1</p>'],
            1 => ['instanceData' => '<p>This is a instance id 1</p>'],
            2 => ['instanceData' => '<p>This is a instance id 2</p>'],
            100 => ['instanceData' => '<p>This is a instance id 100</p>'],
        ];

        $this->cache->setItem('mockPluginData', $this->activeCache);

        parent::__construct($config);
    }

    /**
     * @param int   $instanceId
     * @param array $instanceConfig
     *
     * @return ViewModel
     */
    public function renderInstance($instanceId, $instanceConfig)
    {
        $data = [];

        if (!empty($this->activeCache[$instanceId])) {
            $data = $this->activeCache[$instanceId];
        }

        $view = new ViewModel(
            [
                'data' => $data
            ]
        );
        $view->setTemplate('rcm-mock-plugin/plugin');

        return $view;
    }

    /**
     * @param $instanceId
     * @param $data
     *
     * @return void
     */
    public function saveInstance($instanceId, $data)
    {
        $this->activeCache[$instanceId] = $data;
        $this->cache->setItem('mockPluginData', $this->activeCache);
    }

    /**
     * @param $instanceId
     *
     * @return void
     */
    public function deleteInstance($instanceId)
    {
        if ($instanceId == 5000000) {
            throw new RuntimeException('This call fails on purpose.');
        }

        unset($this->activeCache[$instanceId]);
        $this->cache->setItem('mockPluginData', $this->activeCache);
    }

    /**
     * @param RequestInterface $request
     *
     * @return void
     */
    public function setRequest(RequestInterface $request)
    {

    }
}

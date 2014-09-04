<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rmcnew
 * Date: 10/30/12
 * Time: 3:24 PM
 * To change this template use File | Settings | File Templates.
 */
namespace RcmRssFeed\Controller;

use Rcm\Plugin\BaseController;
use RcmInstanceConfig\Service\PluginStorageMgr;
use Zend\Feed\Reader\Reader;
use Zend\Http\Client;

class ProxyController
    extends BaseController
{

    protected $userMgr;
    protected $cacheMgr;

    function __construct(
        $config,
        \Zend\Cache\Storage\StorageInterface $cacheMgr
    ) {
        parent::__construct($config);
        //$this->userMgr = $userMgr;
        $this->cacheMgr = $cacheMgr;
    }

    public function rssProxyAction()
    {
        $overrideFeedUrl = $this->getEvent()->getRequest()->getQuery()->get(
            'urlOverride'
        );
        $limit = $this->getEvent()->getRequest()->getQuery()->get('limit');
        $instanceId = $this->getEvent()->getRequest()->getQuery()->get(
            'instanceId'
        );

        /** @var \Rcm\Service\PluginManager $pluginManager */
        $pluginManager = $this->serviceLocator->get(
            '\Rcm\Service\PluginManager'
        );

        if ($instanceId > 0) {
            $instanceConfig = $pluginManager->getInstanceConfig($instanceId);
        } else {
            $instanceConfig = $pluginManager
                ->getDefaultInstanceConfig('RcmRssFeed');
        }

        $feedUrl = $instanceConfig['rssFeedUrl'];

        if ($this->cacheMgr->hasItem($feedUrl)) {
            $viewRssData = $this->cacheMgr->getItem($feedUrl);
        }

        if (!empty($overrideFeedUrl) && $overrideFeedUrl != 'null') {
            //$permissions = $this->userMgr->getLoggedInAdminPermissions();
            $permissions = null;
            /**
             * Only admins can override the url. This prevents people from using
             * our proxy to DDOS other sites.
             */
            if (is_a($permissions, '\Rcm\Entity\AdminPermissions')) {
                $feedUrl = $overrideFeedUrl;
            }
        }

        if (empty($limit)) {
            $limit = $instanceConfig['rssFeedLimit'];
        }

        $rssReader = new Reader();

        //Tried to add a timeout like this but it didnt work
        $httpClient = new Client($feedUrl, array('timeout' => 5));
        $rssReader->setHttpClient($httpClient);

        $feedData = $rssReader->import($feedUrl);

        $feedCount = 0;

        $viewRssData = array();

        foreach ($feedData as $entry) {

            if ($feedCount == $limit) {
                break;
            }

            $viewRssData[] = array(
                'feedtitle' => $entry->getTitle(),
                'description' => $entry->getDescription(),
                'dateModified' => $entry->getDateModified(),
                'authors' => $entry->getAuthors(),
                'feedlink' => $entry->getLink()
            );

            $feedCount++;
        }

        $this->cacheMgr->addItem($feedUrl, $viewRssData);

        $this->sendJson($viewRssData);

    }

    private function sendJson($viewRssData)
    {
        $expires = 60 * 5; //Five Minutes
        header("Pragma: public");
        header("Cache-Control: maxage=" . $expires);
        header('Content-type: application/json'); //required for ie8
        header(
            'Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT'
        );

        echo json_encode($viewRssData);
        exit;
    }
}

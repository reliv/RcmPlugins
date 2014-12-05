<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rmcnew
 * Date: 10/30/12
 * Time: 3:24 PM
 * To change this template use File | Settings | File Templates.
 */
namespace RcmRssFeed\Controller;

use Zend\Feed\Reader\Reader;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;

class ProxyController
    extends AbstractActionController
{

    protected $userMgr;
    protected $cacheMgr;
    protected $siteId;

    function __construct(
        $config,
        $siteId,
        \Zend\Cache\Storage\StorageInterface $cacheMgr
    ) {
        $this->cacheMgr = $cacheMgr;
        $this->siteId = $siteId;
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
            $this->sendJson($viewRssData);
        }

        if (!empty($overrideFeedUrl) && $overrideFeedUrl != 'null') {
            //$permissions = $this->userMgr->getLoggedInAdminPermissions();
            $permissions = null;

            /**
             * Only admins can override the url. This prevents people from using
             * our proxy to DDOS other sites.
             */
            $allowed = $this->rcmIsAllowed('sites.' . $this->siteId, 'admin');

            if ($allowed) {
                $feedUrl = $overrideFeedUrl;
            }
        }

        if (empty($limit)) {
            $limit = $instanceConfig['rssFeedLimit'];
        }

        $rssReader = new Reader();

        //Tried to add a timeout like this but it didnt work
        $httpClient = new Client($feedUrl, ['timeout' => 5]);
        $rssReader->setHttpClient($httpClient);

        try {
            $feedData = $rssReader->import($feedUrl);
        } catch (\Exception $e) {
            $feedData = [];
        }

        $feedCount = 0;

        $viewRssData = [];

        foreach ($feedData as $entry) {

            if ($feedCount == $limit) {
                break;
            }

            $viewRssData[] = [
                'feedtitle' => $entry->getTitle(),
                'description' => $entry->getDescription(),
                'dateModified' => $entry->getDateModified(),
                'authors' => $entry->getAuthors(),
                'feedlink' => $entry->getLink()
            ];

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

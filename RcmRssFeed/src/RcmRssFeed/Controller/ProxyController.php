<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rmcnew
 * Date: 10/30/12
 * Time: 3:24 PM
 * To change this template use File | Settings | File Templates.
 */
namespace RcmRssFeed\Controller;

class ProxyController
    extends \RcmJsonDataPluginToolkit\Controller\JsonDataPluginController
{

    protected $userMgr;

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        \Rcm\Model\UserManagement\UserManagerInterface $userMgr
    ) {
        parent::__construct($entityMgr);
        $this->userMgr = $userMgr;
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

        if ($instanceId < 0) {
            $data= $this->getDefaultJsonContent();
        } else {
            $data = $this->readJsonDataFromDb($instanceId)->getData();

        }

        $feedUrl = $data->rssFeedUrl;

        if (!empty($overrideFeedUrl) && $overrideFeedUrl != 'null') {
            $permissions = $this->userMgr->getLoggedInAdminPermissions();
            /**
             * Only admins can override the url. This prevents people from using
             * our proxy to DDOS other sites.
             */
            if(is_a($permissions,'\Rcm\Entity\AdminPermissions')){
                $feedUrl = $overrideFeedUrl;
            }
        }

        if (empty($limit)) {
            $limit = $data->rssFeedLimit;
        }

        $rssReader = new \Zend\Feed\Reader\Reader();
        $data = $rssReader->import($feedUrl);

        $feedCount = 0;

        foreach ($data as $entry) {

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

        $expires = 60 * 5; //Five Minutes
        header("Pragma: public");
        header("Cache-Control: maxage=" . $expires);
        header(
            'Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT'
        );

        echo json_encode($viewRssData);
        exit;
    }
}

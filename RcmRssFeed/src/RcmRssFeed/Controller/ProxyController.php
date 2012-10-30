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
    public function rssProxyAction()
    {
        $feedUrl = $this->getEvent()->getRequest()->getQuery()->get(
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

        if (empty($feedUrl) || $feedUrl == 'null') {
            $feedUrl = $data->rssFeedUrl;
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

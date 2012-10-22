<?php

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\Navigation
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmRssFeed\Controller;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmPlugins\Navigation
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class PluginController
    extends \RcmPluginCommon\Controller\JsonDataPluginController
    implements \Rcm\Controller\PluginControllerInterface
{
    /**
     * @var string template to render content with
     */
    protected $template = 'rcm-rss-feed/plugin';

    public function pluginAction($instanceId) {
        if ($instanceId <0) {
            $content = new \RcmPluginCommon\Entity\JsonContent(
                null, $this->getDefaultJsonContent()
            );
        } else {
            $content = $this->readEntity($instanceId, $this->storageClass);

        }

        $data = $content->getData();

        $feedUrl = $data->rcmRssFeedUrl;
        $limit = $data->rcmRssFeedLimit;
        $headline = $data->headline;

        $view = new \Zend\View\Model\ViewModel();
        $view->setTemplate($this->template);
        $view->setVariable('rssInstanceId', $instanceId);
        $view->setVariable('rssProxy', '/rcm-rss-proxy');
        $view->setVariable('rssUrl', $feedUrl);
        $view->setVariable('rssDisplayLimit', $limit);
        $view->setVariable('rssDisplayHeadline', $headline);

        return $view;
    }

    public function rssProxyAction()
    {
        $feedUrl = $this->getEvent()->getRequest()->getQuery()->get('urlOverride');
        $limit = $this->getEvent()->getRequest()->getQuery()->get('limit');
        $instanceId = $this->getEvent()->getRequest()->getQuery()->get('instanceId');

        if ($instanceId <0) {
            $content = new \RcmPluginCommon\Entity\JsonContent(
                null, $this->getDefaultJsonContent()
            );
        } else {
            $content = $this->readEntity($instanceId, $this->storageClass);

        }

        $data = $content->getData();

        if (empty($feedUrl) || $feedUrl == 'null') {
            $feedUrl = $data->rcmRssFeedUrl;
        }

        if (empty($limit)) {
            $limit = $data->rcmRssFeedLimit;
        }

        $rssReader = new \Zend\Feed\Reader\Reader();
        $data = $rssReader->import($feedUrl);

        $feedCount = 0;

        foreach ($data as $entry) {

            if ($feedCount == $limit) {
                break;
            }

            $viewRssData[] = array (
                'feedtitle'        => $entry->getTitle(),
                'description'  => $entry->getDescription(),
                'dateModified' => $entry->getDateModified(),
                'authors'       => $entry->getAuthors(),
                'feedlink'         => $entry->getLink()
            );

            $feedCount++;
        }

        $expires = 60*5;//Five Minutes
        header("Pragma: public");
        header("Cache-Control: maxage=".$expires);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');

        echo json_encode($viewRssData);
        exit;
    }

}
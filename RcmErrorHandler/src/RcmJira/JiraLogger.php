<?php

namespace RcmJira;

use RcmErrorHandler\Log\AbstractErrorLogger;
use RcmErrorHandler\Log\ErrorLogger;
use RcmJira\Exception\JiraListenerException;
use RcmJira\Exception\JiraLoggerException;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;


/**
 * Class JiraLogger
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJira
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class JiraLogger extends AbstractErrorLogger
{
    /**
     * array(
     * 'projectKey' => 'REF',
     * 'issueType' => 1, // Bug
     * 'enterIssueIfNotStatus' => array(
     *   'closed',
     *   'resolved',
     *  ),
     * ),
     *
     * @var array $options
     */
    protected $options = [];

    /**
     * @var \chobie\Jira\Api|null $api
     */
    protected $api = null;

    /**
     * @param \chobie\Jira\Api $api
     * @param array            $jiraOptions
     */
    public function __construct(\chobie\Jira\Api $api, $jiraOptions = [])
    {
        $this->api = $api;
        $this->options = $jiraOptions;
    }

    /**
     * getApi
     *
     * @return \chobie\Jira\Api|null
     */
    protected function getApi()
    {
        return $this->api;
    }

    /**
     * Add a message as a log entry
     *
     * @param  int               $priority
     * @param  mixed             $message
     * @param  array|Traversable $extra
     *
     * @return Logger
     */
    public function log($priority, $message, $extra = [])
    {
        $summary = $this->prepareSummary($priority, $message);

        $existingIssueKey = $this->getIssueKey($summary);

        if ($existingIssueKey) {
            // Add comment
            $this->addComment($existingIssueKey, $summary, $extra);

        } else {
            // create issue
            $this->createIssue($summary, $extra);
        }

        return $this;
    }

    /**
     * getIssueKey
     *
     * @param $summary
     *
     * @return null
     * @throws JiraLoggerException
     */
    protected function getIssueKey($summary)
    {
        $jql = $this->getIssueJql($summary);

        $result = $this->getApi()->search($jql, 0, 1);

        if ($this->hasApiError($result)) {

            $message = 'An error occured while talking to JIRA (search): ' .
                implode(' ', $result->getResult()['errorMessages']) . ' ' .
                implode(' ', $result->getResult()['errors']);

            throw new JiraLoggerException($message);
        }

        if ($result->getIssuesCount() > 0) {

            $issue = $result->getIssues()[0];
            return $issue->getKey();
        }

        return null;
    }

    /**
     * getIssueJql
     *
     * @param $summary
     *
     * @return string
     * @throws JiraListenerException
     */
    protected function getIssueJql($summary)
    {
        $closedJql = $this->getStatusQuery();

        $projectsJql = $this->getProjectQuery();

        if (empty($projectsJql)) {
            throw new JiraListenerException(
                'No project key has been defined, JQL not valid.'
            );
        }

        $jql = $projectsJql .
            $closedJql .
            'AND (Summary ~ "\"' . $this->jqlEscape($summary) . '\"") ' .
            "ORDER BY createdDate DESC";

        return $jql;
    }

    /**
     * getProjectQuery
     *
     * @return string
     */
    protected function getProjectQuery()
    {
        $projectKey = $this->getOption('projectKey', 'REF');

        if (empty($projectKey)) {

            return '';
        }

        $projects = $this->getOption('projectsToCheckForIssues', null);

        $jql = "project = '" . $this->jqlEscape($projectKey) . "' ";

        if (empty($projects)) {

            return $jql;
        }

        $jqlArr = [];

        foreach ($projects as $project) {

            $jqlArr[] = "project = '" . $this->jqlEscape($project) . "'";
        }

        $jql = "(" . $jql . " OR " . implode(' OR ', $jqlArr) . ") ";

        return $jql;
    }

    /**
     * getStatusQuery
     *
     * @return string
     */
    protected function getStatusQuery()
    {
        $statuses = $this->getOption('enterIssueIfNotStatus', null);

        if (empty($statuses)) {

            return '';
        }

        foreach ($statuses as &$status) {

            $status = "status != '" . $this->jqlEscape($status) . "'";
        }

        $closedJql = 'AND (' . implode(' and ', $statuses) . ') ';

        return $closedJql;
    }

    /**
     * addComment
     *
     * @param string $issueKey
     * @param string $summary
     * @param array  $extra
     *
     * @return void
     * @throws JiraLoggerException
     */
    protected function addComment($issueKey, $summary, $extra = [])
    {
        $result = $this->getApi()->addComment(
            $issueKey,
            'Error occured again: ' . $summary
        );

        if ($this->hasApiError($result)) {

            $message = 'An error occured while talking to JIRA (addComment): ' .
                implode(' ', $result->getResult()['errorMessages']) . ' ' .
                implode(' ', $result->getResult()['errors']);

            throw new JiraLoggerException($message);
        }
    }

    /**
     * createIssue
     *
     * @param string $summary
     * @param array  $extra
     *
     * @return void
     * @throws JiraLoggerException
     */
    protected function createIssue($summary, $extra = [])
    {
        $options = [
            "description" => $this->getDescription($extra)
        ];

        $projectKey = $this->getOption('projectKey', 'REF');

        $issueType = (int)$this->getOption('issueType', 1);

        $result = $this->getApi()->createIssue(
            $projectKey,
            $summary,
            $issueType,
            $options
        );

        if ($this->hasApiError($result)) {

            $message
                = 'An error occured while talking to JIRA (createIssue): ' .
                implode(' ', $result->getResult()['errorMessages']) . ' ' .
                implode(' ', $result->getResult()['errors']);

            throw new JiraLoggerException($message);
        }
    }

    /**
     * jqlEscape
     *
     * @param $param
     *
     * @return string
     */
    protected function jqlEscape($param)
    {
        return addslashes($param);
    }

    /**
     * hasApiError
     *
     * @param $result
     *
     * @return bool
     */
    protected function hasApiError($result)
    {
        $apiResult = $result->getResult();

        if (isset($apiResult['errors'])) {
            return true;
        }

        return false;
    }
}

<?php

namespace RcmJira\Test;

require_once __DIR__ . '/../Mocks.php';

use RcmErrorHandler\Test\Mocks;
use RcmJira\JiraLogger;

class JiraLoggerTest extends Mocks {

    public function testCase1(){

        $logger = new JiraLogger(
            $this->getMockJiraApi(
                $this->getMockApiResult(),
                $this->getMockApiResult(),
                $this->getMockApiResult()
            ),
            [
                'projectKey' => 'test1',
            ]
        );

        $_SERVER['REQUEST_URI'] = 'http://test.example.com';

        $extra = [
            'description' => 'description',
            'file' => 'file',
            'line' => 'line',
            'message' => 'message',
            'trace' => 'trace',
        ];

        $logger->emerg('test messg', $extra);
        $logger->alert('test messg', $extra);
        $logger->crit('test messg', $extra);
        $logger->err('test messg', $extra);
        $logger->warn('test messg', $extra);
        $logger->notice('test messg', $extra);
        $logger->info('test messg', $extra);
        $logger->debug('test messg', $extra);

        $logger->log(E_NOTICE, 'test messg', $extra);
        $logger->log(0, 'test messg', $extra);

        $logger->getPriorityFromErrorNumber(E_NOTICE);
        $logger->getPriorityFromErrorNumber(0);

    }

    public function testCase2(){

        $logger = new JiraLogger(
            $this->getMockJiraApi(
                $this->getMockApiResult(),
                $this->getMockApiResult(),
                $this->getMockApiResult()
            ),
            [
                'projectKey' => 'test1',
                'enterIssueIfNotStatus' => ['status'],
            ]
        );

        $_SERVER['REQUEST_URI'] = 'http://test.example.com';

        $extra = [
            'description' => 'description',
            'file' => 'file',
            'line' => 'line',
            'message' => 'message',
            'trace' => 'trace',
        ];

        $logger->log(E_NOTICE, 'test messg', $extra);

    }

    public function testCaseErrSearch(){

        $logger = new JiraLogger(
            $this->getMockJiraApi(
                $this->getMockApiResultErr(),
                $this->getMockApiResult(),
                $this->getMockApiResult()
            ),
            [
                'projectKey' => 'test1',
                'enterIssueIfNotStatus' => ['status'],
            ]
        );

        $_SERVER['REQUEST_URI'] = 'http://test.example.com';

        $extra = [
            'description' => 'description',
            'file' => 'file',
            'line' => 'line',
            'message' => 'message',
            'trace' => 'trace',
        ];


        try{
            $logger->log(E_NOTICE, 'test messg', $extra);

        } catch(\Exception $e) {

            $this->assertInstanceOf(
                '\RcmJira\Exception\JiraLoggerException',
                $e
            );

            //echo $e->getMessage();

            return;
        }

        $this->fail("Exception thrown incorrectly");
    }

    public function testCaseErrComment(){

        $logger = new JiraLogger(
            $this->getMockJiraApi(
                $this->getMockApiResult($this->getMockApiIssue()),
                $this->getMockApiResultErr(),
                $this->getMockApiResult()
            ),
            [
                'projectKey' => 'test1',
                'enterIssueIfNotStatus' => ['status'],
            ]
        );

        $_SERVER['REQUEST_URI'] = 'http://test.example.com';

        $extra = [
            'description' => 'description',
            'file' => 'file',
            'line' => 'line',
            'message' => 'message',
            'trace' => 'trace',
        ];


        try{
            $logger->log(E_NOTICE, 'test messg', $extra);

        } catch(\Exception $e) {

            $this->assertInstanceOf(
                '\RcmJira\Exception\JiraLoggerException',
                $e
            );

            //echo $e->getMessage();

            return;
        }

        $this->fail("Exception thrown incorrectly");

    }

    public function testCaseErrAdd(){

        $logger = new JiraLogger(
            $this->getMockJiraApi(
                $this->getMockApiResult(),
                $this->getMockApiResult(),
                $this->getMockApiResultErr()
            ),
            [
                'projectKey' => 'test1',
                'enterIssueIfNotStatus' => ['status'],
            ]
        );

        $_SERVER['REQUEST_URI'] = 'http://test.example.com';

        $extra = [
            'description' => 'description',
            'file' => 'file',
            'line' => 'line',
            'message' => 'message',
            'trace' => 'trace',
        ];


        try{
            $logger->log(E_NOTICE, 'test messg', $extra);

        } catch(\Exception $e) {

            $this->assertInstanceOf(
                '\RcmJira\Exception\JiraLoggerException',
                $e
            );

            //echo $e->getMessage();

            return;
        }

        $this->fail("Exception thrown incorrectly");

    }
}
 
<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\LoggerClient;

class LoggerClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $response;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * @var LoggerClient
     */
    private $loggerClient;

    protected function setUp()
    {
        parent::setUp();
        $this->client = $this->getMockBuilder('Elastification\Client\ClientInterface')
            ->getMock();

        $this->request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->getMock();

        $this->response = $this->getMockBuilder('Elastification\Client\Response\ResponseInterface')
            ->getMock();

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->getMock();

        /** @noinspection PhpParamsInspection */
        $this->loggerClient = new LoggerClient($this->client, $this->logger);
    }

    protected function tearDown()
    {
        $this->client = null;
        $this->request = null;
        $this->logger = null;
        $this->loggerClient = null;
        $this->response = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\ClientInterface', $this->loggerClient);
        $this->assertInstanceOf('Elastification\Client\LoggerClient', $this->loggerClient);
    }

    public function testGetRequest()
    {
        $this->client
            ->expects($this->once())
            ->method('getRequest')
            ->with($this->equalTo('test-request'))
            ->willReturn($this->request);

        $request = $this->loggerClient->getRequest('test-request');
        $this->assertSame($this->request, $request);
    }

    public function testSend()
    {
        $this->configureRequest();

        $this->response
            ->expects($this->once())
            ->method('getRawData')
            ->willReturn('getRawData');

        $this->client
            ->expects($this->once())
            ->method('send')
            ->with($this->equalTo($this->request))
            ->willReturn($this->response);

        $this->logger
            ->expects($this->never())
            ->method('error');

        $this->logger
            ->expects($this->exactly(2))
            ->method('debug')
            ->withConsecutive(
                array($this->stringContains('time taken: ')),
                array($this->stringContains('response: '), $this->callback(function($subject){
                    return (
                        array_key_exists('class', $subject)
                        && array_key_exists('raw_data', $subject)
                    );
                }))
            );

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with($this->stringContains('request: '), $this->callback(function($subject){
                return (
                    array_key_exists('class', $subject)
                    && array_key_exists('method', $subject)
                    && array_key_exists('index', $subject)
                    && array_key_exists('type', $subject)
                    && array_key_exists('action', $subject)
                    && array_key_exists('response_class', $subject)
                    && array_key_exists('body', $subject)
                    );
            }));


        $response = $this->loggerClient->send($this->request);
        $this->assertSame($this->response, $response);
    }

    public function testSendException()
    {
        $this->configureRequest();

        $this->response
            ->expects($this->never())
            ->method('getRawData');

        $this->client
            ->expects($this->once())
            ->method('send')
            ->with($this->equalTo($this->request))
            ->willThrowException(new ClientException('test exception'));

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->logger
            ->expects($this->never())
            ->method('debug');

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with($this->stringContains('request: '), $this->callback(function($subject){
                return (
                    array_key_exists('class', $subject)
                    && array_key_exists('method', $subject)
                    && array_key_exists('index', $subject)
                    && array_key_exists('type', $subject)
                    && array_key_exists('action', $subject)
                    && array_key_exists('response_class', $subject)
                    && array_key_exists('body', $subject)
                );
            }));


        try {
            $this->loggerClient->send($this->request);
        } catch(ClientException $exception) {
            $this->assertContains('test exception', $exception->getMessage());
            return;
        }

        $this->fail();

    }



    private function configureRequest()
    {
        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('getMethod');

        $this->request
            ->expects($this->once())
            ->method('getIndex')
            ->willReturn('getIndex');

        $this->request
            ->expects($this->once())
            ->method('getType')
            ->willReturn('getType');

        $this->request
            ->expects($this->once())
            ->method('getAction')
            ->willReturn('getAction');

        $this->request
            ->expects($this->once())
            ->method('getSupportedClass')
            ->willReturn('getSupportedClass');

        $this->request
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('getBody');
    }

}

<?php
namespace Elastification\Client\Tests\Fixtures\Unit\Transport\Guzzle;

use Elastification\Client\Transport\HttpGuzzle\GuzzleTransportRequest;
use Elastification\Client\Transport\HttpGuzzle\GuzzleTransportResponse;

class GuzzleRequestResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterMethodsArePipedToWrappedRequest()
    {
        $fixtureBody = 'test body';
        $fixturePath = '/some/index';
        $fixtureQuery = ['size' => 5];

        $orgRequest = $this->getMockBuilder('GuzzleHttp\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $orgRequest->expects($this->once())->method('setBody')->with($this->isInstanceOf('GuzzleHttp\Stream\Stream'));
        $orgRequest->expects($this->once())->method('setPath')->with($this->equalTo($fixturePath));
        $orgRequest->expects($this->once())->method('setQuery')->with($this->equalTo($fixtureQuery));

        $guzzleRequest = new GuzzleTransportRequest($orgRequest);

        $guzzleRequest->setBody($fixtureBody);
        $guzzleRequest->setPath($fixturePath);
        $guzzleRequest->setQueryParams($fixtureQuery);
    }

    public function testResponse()
    {
        $testResponseBody = 'test';
        $orgResponse = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $orgResponse->expects($this->once())->method('getBody')->willReturn($testResponseBody);

        $guzzleResponse = new GuzzleTransportResponse($orgResponse);

        $this->assertEquals($testResponseBody, $guzzleResponse->getBody());
    }
}


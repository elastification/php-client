<?php
namespace Elastification\Client\Tests\Fixtures\Unit\Transport\Guzzle;

use Elastification\Client\Transport\HttpGuzzle\GuzzleTransportRequest;
use Elastification\Client\Transport\HttpGuzzle\GuzzleTransportResponse;
use GuzzleHttp\Psr7\Request;

class GuzzleRequestResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterMethodsArePipedToWrappedRequestSetBody()
    {
        $fixtureBody = 'test body';

        $orgRequest = $this->getMockBuilder('Psr\Http\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $orgRequest->expects($this->once())->method('withBody')->with($this->isInstanceOf('Psr\Http\Message\StreamInterface'));

        $guzzleRequest = new GuzzleTransportRequest($orgRequest);

        $guzzleRequest->setBody($fixtureBody);
    }

    public function testSetterMethodsArePipedToWrappedRequestSetPath()
    {
        $fixturePath = '/some/index';

        $orgRequest = $this->getMockBuilder('Psr\Http\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $uri = $this->getMockBuilder('Psr\Http\Message\UriInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $uri->expects($this->once())->method('withPath')->with($this->equalTo($fixturePath))->willReturn($uri);
        $orgRequest->expects($this->once())->method('getMethod');
        $orgRequest->expects($this->once())->method('getUri')->willReturn($uri);

        $guzzleRequest = new GuzzleTransportRequest($orgRequest);

        $guzzleRequest->setPath($fixturePath);
    }

    public function testSetterMethodsArePipedToWrappedRequestSetQuery()
    {
        $fixtureQuery = ['size' => 5];

        $orgRequest = $this->getMockBuilder('Psr\Http\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $uri = $this->getMockBuilder('Psr\Http\Message\UriInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $uri->expects($this->once())->method('withQuery')->with($this->equalTo(\GuzzleHttp\Psr7\build_query($fixtureQuery)))->willReturn($uri);
        $orgRequest->expects($this->once())->method('getUri')->willReturn($uri);
        $orgRequest->expects($this->once())->method('withUri')->with($uri);

        $guzzleRequest = new GuzzleTransportRequest($orgRequest);

        $guzzleRequest->setQueryParams($fixtureQuery);
    }


    public function testResponse()
    {
        $testResponseBody = 'test';
        $orgResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $orgResponse->expects($this->once())->method('getBody')->willReturn($testResponseBody);

        $guzzleResponse = new GuzzleTransportResponse($orgResponse);

        $this->assertEquals($testResponseBody, $guzzleResponse->getBody());
    }

    public function testRequestBodyIsNotEmptyAfterQueryIsChanged()
    {
        $orgRequest = new Request('POST', '', [], 'body_data');

        $transportRequest = new GuzzleTransportRequest($orgRequest);
        $transportRequest->setQueryParams(['a' => 'b']);

        $this->assertEquals('body_data', (string)$transportRequest->getWrappedRequest()->getBody());
    }
}


<?php
namespace Elastification\Client\Tests\Fixtures\Unit\Transport\Guzzle;

use Elastification\Client\Transport\HttpGuzzle\GuzzleTransport;

class GuzzleTransportTest extends \PHPUnit_Framework_TestCase
{

    public function testGuzzleTransportCreateRequest()
    {
        $requestMethod = 'POST';
        $guzzleClientMock = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleRequestMock = $this->getMockBuilder('GuzzleHttp\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleClientMock->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo($requestMethod))
            ->willReturn($guzzleRequestMock);

        $guzzleTransport = new GuzzleTransport($guzzleClientMock);
        $request = $guzzleTransport->createRequest($requestMethod);

        $this->assertInstanceOf('Elastification\Client\Transport\HttpGuzzle\GuzzleTransportRequest', $request);
    }

    public function testGuzzleTransportSendRequest()
    {
        $requestMethod = 'POST';
        $guzzleClientMock = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleRequestMock = $this->getMockBuilder('GuzzleHttp\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleResponseMock = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleClientMock->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo($requestMethod))
            ->willReturn($guzzleRequestMock);

        $guzzleClientMock->expects($this->once())
            ->method('send')
            ->with($this->equalTo($guzzleRequestMock))
            ->willReturn($guzzleResponseMock);

        $guzzleTransport = new GuzzleTransport($guzzleClientMock);
        $request = $guzzleTransport->createRequest($requestMethod);

        $response = $guzzleTransport->send($request);
        $this->assertInstanceOf('Elastification\Client\Transport\HttpGuzzle\GuzzleTransportResponse', $response);
    }
}


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

        $guzzleTransport = new GuzzleTransport($guzzleClientMock);
        $request = $guzzleTransport->createRequest($requestMethod);

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request->getWrappedRequest());
        $this->assertSame($requestMethod, $request->getWrappedRequest()->getMethod());

        $this->assertInstanceOf('Elastification\Client\Transport\HttpGuzzle\GuzzleTransportRequest', $request);
    }

    public function testGuzzleTransportSendRequest()
    {
        $guzzleClientMock = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleRequestMock = $this->getMockBuilder('Psr\Http\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleResponseMock = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleClientMock->expects($this->once())
            ->method('send')
            ->with($this->equalTo($guzzleRequestMock))
            ->willReturn($guzzleResponseMock);

        $transportRequestMock = $this->getMockBuilder('Elastification\Client\Transport\HttpGuzzle\GuzzleTransportRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $transportRequestMock->expects($this->once())->method('getWrappedRequest')->willReturn($guzzleRequestMock);

        $guzzleTransport = new GuzzleTransport($guzzleClientMock);

        $response = $guzzleTransport->send($transportRequestMock);
        $this->assertInstanceOf('Elastification\Client\Transport\HttpGuzzle\GuzzleTransportResponse', $response);
    }

    public function testGuzzleTransportSendRequestWithException()
    {
        $this->setExpectedException('Elastification\Client\Transport\Exception\TransportLayerException');

        $guzzleClientMock = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleRequestMock = $this->getMockBuilder('Psr\Http\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleClientMock->expects($this->once())
            ->method('send')
            ->with($this->equalTo($guzzleRequestMock))
            ->willThrowException(new \Exception('Something went wrong'));

        $transportRequestMock = $this->getMockBuilder('Elastification\Client\Transport\HttpGuzzle\GuzzleTransportRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $transportRequestMock->expects($this->once())->method('getWrappedRequest')->willReturn($guzzleRequestMock);


        $guzzleTransport = new GuzzleTransport($guzzleClientMock);
        $guzzleTransport->send($transportRequestMock);
    }
}


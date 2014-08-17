<?php
namespace Elastification\Client\Tests\Fixtures\Unit\Transport\Thrift;

use Elastification\Client\Transport\Thrift\ThriftTransport;

/**
 * @package Elastification\Client\Tests\Fixtures\Unit\Transport\Thrift
 * @author  Mario Mueller <mueller@freshcells.de>
 */
class ThriftTransportTest extends \PHPUnit_Framework_TestCase
{

    public function testThriftCreateRequest()
    {
        $requestMethod = 'POST';
        $thriftClient = $this->getMockBuilder('Elasticsearch\RestClient')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftTransport = new ThriftTransport($thriftClient);
        $request = $thriftTransport->createRequest($requestMethod);

        $this->assertInstanceOf('Elastification\Client\Transport\Thrift\ThriftTransportRequest', $request);
    }

    public function testThriftTransportSendRequest()
    {
        $thriftClient = $this->getMockBuilder('Elasticsearch\RestClient')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftNativeRequest = $this->getMockBuilder('Elasticsearch\RestRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftNativeResponse = $this->getMockBuilder('Elasticsearch\RestResponse')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftTransportRequest = $this->getMockBuilder('Elastification\Client\Transport\Thrift\ThriftTransportRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftTransportRequest->expects($this->once())
            ->method('getWrappedRequest')
            ->willReturn($thriftNativeRequest);

        $thriftClient->expects($this->once())
            ->method('execute')
            ->with($this->identicalTo($thriftNativeRequest))
            ->willReturn($thriftNativeResponse);

        $thriftTransport = new ThriftTransport($thriftClient);

        $response = $thriftTransport->send($thriftTransportRequest);
        $this->assertInstanceOf('Elastification\Client\Transport\Thrift\ThriftTransportResponse', $response);
    }

    public function testThriftTransportSendRequestWithException()
    {
        $this->setExpectedException('Elastification\Client\Transport\Exception\TransportLayerException');

        $thriftClient = $this->getMockBuilder('Elasticsearch\RestClient')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftNativeRequest = $this->getMockBuilder('Elasticsearch\RestRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftTransportRequest = $this->getMockBuilder('Elastification\Client\Transport\Thrift\ThriftTransportRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $thriftTransportRequest->expects($this->once())
            ->method('getWrappedRequest')
            ->willReturn($thriftNativeRequest);

        $thriftClient->expects($this->once())
            ->method('execute')
            ->with($this->identicalTo($thriftNativeRequest))
            ->willThrowException(new \Exception('This should never happen!'));

        $thriftTransport = new ThriftTransport($thriftClient);
        $thriftTransport->send($thriftTransportRequest);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit;

use Elastification\Client\Client;
use Elastification\Client\ClientInterface;
use Elastification\Client\Exception\ClientException;
use Elastification\Client\Exception\RequestException;
use Elastification\Client\Transport\Exception\TransportLayerException;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->requestManager = $this->getMockBuilder('Elastification\Client\Request\RequestManagerInterface')
            ->getMock();

        $this->transport = $this->getMockBuilder('Elastification\Client\Transport\TransportInterface')
            ->getMock();

        /** @noinspection PhpParamsInspection */
        $this->client = new Client($this->transport, $this->requestManager);
    }

    protected function tearDown()
    {
        $this->requestManager = null;
        $this->transport = null;
        $this->client = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\ClientInterface', $this->client);
        $this->assertInstanceOf('Elastification\Client\Client', $this->client);
    }

    public function testGetRequest()
    {
        $requestName = 'test-request.name';
        $request = 'this should be a request object';

        $this->requestManager->expects($this->once())
            ->method('getRequest')
            ->with($this->equalTo($requestName))
            ->will($this->returnValue($request));

        $result = $this->client->getRequest($requestName);

        $this->assertSame($request, $result);
    }

    public function testClientVersionConstruct()
    {
        /** @noinspection PhpParamsInspection */
        $this->client = new Client($this->transport, $this->requestManager, 'v0.90.x');

        $requestName = 'test-request.name';
        $request = 'this should be a request object';

        $this->requestManager->expects($this->once())
            ->method('getRequest')
            ->with($this->equalTo($requestName))
            ->will($this->returnValue($request));

        $result = $this->client->getRequest($requestName);

        $this->assertSame($request, $result);
    }

    public function testSendWrongRequestMethod()
    {
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->getMock();

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('NOTALLOWED'));

        try {
            /** @noinspection PhpParamsInspection */
            $this->client->send($request);
        } catch (RequestException $exception) {
            $this->assertSame('request method is not allowed', $exception->getMessage());
            return;
        }

        $this->fail();
    }

    public function testSendWithoutBody()
    {
        $method = 'GET';
        $index = 'test-index';
        $type = 'test-type';
        $action = 'test-action';
        $divider = ClientInterface::PATH_DIVIDER;
        $path = $index . $divider . $type . $divider . $action;
        $responseBody = 'this is a simple response body for this mocking hell';
        $serializerParams = array();
        $supportedClass = 'Elastification\Client\Response\ResponseInterface';

        //serializer
        $serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->getMock();

        //response
        $response = $this->getMockBuilder('Elastification\Client\Response\ResponseInterface')
            ->getMock();

        //request
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->getMock();

        $request->expects($this->exactly(2))
            ->method('getMethod')
            ->will($this->returnValue($method));

        $request->expects($this->exactly(2))
            ->method('getIndex')
            ->will($this->returnValue($index));

        $request->expects($this->exactly(2))
            ->method('getType')
            ->will($this->returnValue($type));

        $request->expects($this->exactly(2))
            ->method('getAction')
            ->will($this->returnValue($action));

        $request->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(null));

        $request->expects($this->once())
            ->method('getSerializer')
            ->will($this->returnValue($serializer));

        $request->expects($this->once())
            ->method('getParameters')
            ->will($this->returnValue(null));

        $request->expects($this->once())
            ->method('getSerializerParams')
            ->will($this->returnValue($serializerParams));

        $request->expects($this->once())
            ->method('createResponse')
            ->with(
                $this->equalTo($responseBody),
                $this->identicalTo($serializer),
                $this->identicalTo($serializerParams)
            )
            ->willReturn($response);

        $request->expects($this->once())
            ->method('getSupportedClass')
            ->willReturn($supportedClass);

        //transport
        $transportRequest = $this->getMockBuilder('Elastification\Client\Transport\TransportRequestInterface')
            ->getMock();

        $transportRequest->expects($this->once())
            ->method('setPath')
            ->with($this->equalTo($path));

        $transportRequest->expects($this->never())
            ->method('setQueryParams');

        $this->transport->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo($method))
            ->willReturn($transportRequest);

        $transportResponse = $this->getMockBuilder('Elastification\Client\Transport\TransportResponseInterface')
            ->getMock();

        $transportResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($responseBody);

        $this->transport->expects($this->once())
            ->method('send')
            ->with($this->identicalTo($transportRequest))
            ->willReturn($transportResponse);



        /** @noinspection PhpParamsInspection */
        $clentResponse = $this->client->send($request);

        $this->assertSame($response, $clentResponse);
    }

    public function testSendWithParamsWithoutBody()
    {
        $method = 'GET';
        $index = 'test-index';
        $type = 'test-type';
        $action = 'test-action';
        $divider = ClientInterface::PATH_DIVIDER;
        $path = $index . $divider . $type . $divider . $action;
        $responseBody = 'this is a simple response body for this mocking hell';
        $serializerParams = array();
        $supportedClass = 'Elastification\Client\Response\ResponseInterface';
        $params = array('my-param' => 'my-value');

        //serializer
        $serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->getMock();

        //response
        $response = $this->getMockBuilder('Elastification\Client\Response\ResponseInterface')
            ->getMock();

        //request
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->getMock();

        $request->expects($this->exactly(2))
            ->method('getMethod')
            ->will($this->returnValue($method));

        $request->expects($this->exactly(2))
            ->method('getIndex')
            ->will($this->returnValue($index));

        $request->expects($this->exactly(2))
            ->method('getType')
            ->will($this->returnValue($type));

        $request->expects($this->exactly(2))
            ->method('getAction')
            ->will($this->returnValue($action));

        $request->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(null));

        $request->expects($this->once())
            ->method('getSerializer')
            ->will($this->returnValue($serializer));

        $request->expects($this->once())
            ->method('getParameters')
            ->willReturn($params);

        $request->expects($this->once())
            ->method('getSerializerParams')
            ->will($this->returnValue($serializerParams));

        $request->expects($this->once())
            ->method('createResponse')
            ->with(
                $this->equalTo($responseBody),
                $this->identicalTo($serializer),
                $this->identicalTo($serializerParams)
            )
            ->willReturn($response);

        $request->expects($this->once())
            ->method('getSupportedClass')
            ->willReturn($supportedClass);

        //transport
        $transportRequest = $this->getMockBuilder('Elastification\Client\Transport\TransportRequestInterface')
            ->getMock();

        $transportRequest->expects($this->once())
            ->method('setPath')
            ->with($this->equalTo($path));

        $transportRequest->expects($this->once())
            ->method('setQueryParams')
            ->with($this->identicalTo($params));

        $this->transport->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo($method))
            ->willReturn($transportRequest);

        $transportResponse = $this->getMockBuilder('Elastification\Client\Transport\TransportResponseInterface')
            ->getMock();

        $transportResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($responseBody);

        $this->transport->expects($this->once())
            ->method('send')
            ->with($this->identicalTo($transportRequest))
            ->willReturn($transportResponse);


        /** @noinspection PhpParamsInspection */
        $clentResponse = $this->client->send($request);

        $this->assertSame($response, $clentResponse);
    }

    public function testSendWithoutBodySupportedClassException()
    {
        $method = 'GET';
        $index = 'test-index';
        $type = 'test-type';
        $action = 'test-action';
        $divider = ClientInterface::PATH_DIVIDER;
        $path = $index . $divider . $type . $divider . $action;
        $responseBody = 'this is a simple response body for this mocking hell';
        $serializerParams = array();
        $supportedClass = 'Elastification\Client\Response\Response';

        //serializer
        $serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->getMock();

        //response
        $response = $this->getMockBuilder('Elastification\Client\Response\ResponseInterface')
            ->getMock();

        //request
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->getMock();

        $request->expects($this->exactly(2))
            ->method('getMethod')
            ->will($this->returnValue($method));

        $request->expects($this->exactly(2))
            ->method('getIndex')
            ->will($this->returnValue($index));

        $request->expects($this->exactly(2))
            ->method('getType')
            ->will($this->returnValue($type));

        $request->expects($this->exactly(2))
            ->method('getAction')
            ->will($this->returnValue($action));

        $request->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(null));

        $request->expects($this->once())
            ->method('getSerializer')
            ->will($this->returnValue($serializer));

        $request->expects($this->once())
            ->method('getSerializerParams')
            ->will($this->returnValue($serializerParams));

        $request->expects($this->once())
            ->method('createResponse')
            ->with(
                $this->equalTo($responseBody),
                $this->identicalTo($serializer),
                $this->identicalTo($serializerParams)
            )
            ->willReturn($response);

        $request->expects($this->once())
            ->method('getSupportedClass')
            ->willReturn($supportedClass);

        //transport
        $transportRequest = $this->getMockBuilder('Elastification\Client\Transport\TransportRequestInterface')
            ->getMock();

        $transportRequest->expects($this->once())
            ->method('setPath')
            ->with($this->equalTo($path));

        $this->transport->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo($method))
            ->willReturn($transportRequest);

        $transportResponse = $this->getMockBuilder('Elastification\Client\Transport\TransportResponseInterface')
            ->getMock();

        $transportResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($responseBody);

        $this->transport->expects($this->once())
            ->method('send')
            ->with($this->identicalTo($transportRequest))
            ->willReturn($transportResponse);


        try {
            /** @noinspection PhpParamsInspection */
            $this->client->send($request);
        } catch (ClientException $exception) {
            $this->assertSame('response is not an instance of ' . $supportedClass, $exception->getMessage());
            return;
        }

        $this->fail();
    }

    public function testSendWithBody()
    {
        $method = 'GET';
        $index = 'test-index';
        $type = 'test-type';
        $action = 'test-action';
        $body = 'mocking hell kept alive by CLub Mate';
        $divider = ClientInterface::PATH_DIVIDER;
        $path = $index . $divider . $type . $divider . $action;
        $responseBody = 'this is a simple response body for this mocking hell';
        $serializerParams = array();
        $supportedClass = 'Elastification\Client\Response\ResponseInterface';

        //serializer
        $serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->getMock();

        //response
        $response = $this->getMockBuilder('Elastification\Client\Response\ResponseInterface')
            ->getMock();

        //request
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->getMock();

        $request->expects($this->exactly(2))
            ->method('getMethod')
            ->will($this->returnValue($method));

        $request->expects($this->exactly(2))
            ->method('getIndex')
            ->will($this->returnValue($index));

        $request->expects($this->exactly(2))
            ->method('getType')
            ->will($this->returnValue($type));

        $request->expects($this->exactly(2))
            ->method('getAction')
            ->will($this->returnValue($action));

        $request->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($body));

        $request->expects($this->once())
            ->method('getSerializer')
            ->will($this->returnValue($serializer));

        $request->expects($this->once())
            ->method('getSerializerParams')
            ->will($this->returnValue($serializerParams));

        $request->expects($this->once())
            ->method('createResponse')
            ->with(
                $this->equalTo($responseBody),
                $this->identicalTo($serializer),
                $this->identicalTo($serializerParams)
            )
            ->willReturn($response);

        $request->expects($this->once())
            ->method('getSupportedClass')
            ->willReturn($supportedClass);

        //transport
        $transportRequest = $this->getMockBuilder('Elastification\Client\Transport\TransportRequestInterface')
            ->getMock();

        $transportRequest->expects($this->once())
            ->method('setPath')
            ->with($this->equalTo($path));

        $transportRequest->expects($this->once())
            ->method('setBody')
            ->with($this->equalTo($body));

        $this->transport->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo($method))
            ->willReturn($transportRequest);

        $transportResponse = $this->getMockBuilder('Elastification\Client\Transport\TransportResponseInterface')
            ->getMock();

        $transportResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($responseBody);

        $this->transport->expects($this->once())
            ->method('send')
            ->with($this->identicalTo($transportRequest))
            ->willReturn($transportResponse);


        /** @noinspection PhpParamsInspection */
        $clentResponse = $this->client->send($request);

        $this->assertSame($response, $clentResponse);
    }

    public function testSendWithoutBodyTransportLayerException()
    {
        $method = 'GET';
        $index = 'test-index';
        $type = 'test-type';
        $action = 'test-action';
        $divider = ClientInterface::PATH_DIVIDER;
        $path = $index . $divider . $type . $divider . $action;
        $transportLayerExceptionMsg = 'tle msg';

        //request
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->getMock();

        $request->expects($this->exactly(2))
            ->method('getMethod')
            ->will($this->returnValue($method));

        $request->expects($this->exactly(2))
            ->method('getIndex')
            ->will($this->returnValue($index));

        $request->expects($this->exactly(2))
            ->method('getType')
            ->will($this->returnValue($type));

        $request->expects($this->exactly(2))
            ->method('getAction')
            ->will($this->returnValue($action));

        $request->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(null));

        //transport
        $transportRequest = $this->getMockBuilder('Elastification\Client\Transport\TransportRequestInterface')
            ->getMock();

        $transportRequest->expects($this->once())
            ->method('setPath')
            ->with($this->equalTo($path));

        $this->transport->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo($method))
            ->willReturn($transportRequest);

        $this->transport->expects($this->once())
            ->method('send')
            ->with($this->identicalTo($transportRequest))
            ->willThrowException(new TransportLayerException($transportLayerExceptionMsg));


        try {
            /** @noinspection PhpParamsInspection */
            $this->client->send($request);
        } catch (ClientException $exception) {
            $this->assertSame($transportLayerExceptionMsg, $exception->getMessage());
            return;
        }

        $this->fail();
    }

}

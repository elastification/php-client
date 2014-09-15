<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V090x;

use Elastification\Client\Response\V090x\NodeInfoResponse;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class NodeInfoResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        $this->serializer = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V090x\NodeInfoResponse', $response);
    }

    public function testGetTaglineArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeArrayGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data[NodeInfoResponse::PROP_TAGLINE], $response->getTagline());
    }

    public function testGetTaglineObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo([])
            )
            ->will($this->returnValue(new NativeObjectGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data->{NodeInfoResponse::PROP_TAGLINE}, $response->getTagline());
    }

    public function testGetVersionArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeArrayGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data[NodeInfoResponse::PROP_VERSION], $response->getVersion());
    }

    public function testGetVersionObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeObjectGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data->{NodeInfoResponse::PROP_VERSION}, $response->getVersion());
    }

    public function testGetStatusArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeArrayGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data[NodeInfoResponse::PROP_STATUS], $response->getStatus());
    }

    public function testGetStatusObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeObjectGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data->{NodeInfoResponse::PROP_STATUS}, $response->getStatus());
    }

    public function testGetNameArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeArrayGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data[NodeInfoResponse::PROP_NAME], $response->getName());
    }

    public function testGetNameObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeObjectGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data->{NodeInfoResponse::PROP_NAME}, $response->getName());
    }

    public function testIsOkArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeArrayGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data[NodeInfoResponse::PROP_OK], $response->isOk());
    }

    public function testIsOkObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeObjectGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new NodeInfoResponse($data, $this->serializer);
        $this->assertSame($data->{NodeInfoResponse::PROP_OK}, $response->isOk());
    }

    private function getData($asObject = false)
    {
        $data = [
            NodeInfoResponse::PROP_OK => true,
            NodeInfoResponse::PROP_NAME => 'My test Name',
            NodeInfoResponse::PROP_STATUS => 'ok',
            NodeInfoResponse::PROP_VERSION => array(
                'number' => '0.90.13',
                'build_hash' => md5('test hash')
            ),
            NodeInfoResponse::PROP_TAGLINE => 'hello test tagline'
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }
}

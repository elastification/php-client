<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V090x\Index;

use Elastification\Client\Response\V090x\Index\IndexStatsResponse;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class IndexStatsResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new IndexStatsResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V090x\Index\IndexStatsResponse', $response);
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data[IndexStatsResponse::PROP_OK], $response->isOk());
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data->{IndexStatsResponse::PROP_OK}, $response->isOk());
    }

    public function testGetAllArray()
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data[IndexStatsResponse::PROP_ALL], $response->getAll());
    }

    public function testGetAllObject()
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data->{IndexStatsResponse::PROP_ALL}, $response->getAll());
    }

    public function testGetShardsArray()
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data[IndexStatsResponse::PROP_SHARDS], $response->getShards());
    }

    public function testGetShardsObject()
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data->{IndexStatsResponse::PROP_SHARDS}, $response->getShards());
    }

    public function testGetIndicesArray()
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data[IndexStatsResponse::PROP_INDICES], $response->getIndices());
    }

    public function testGetIndicesObject()
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
        $response = new IndexStatsResponse($data, $this->serializer);
        $this->assertSame($data->{IndexStatsResponse::PROP_INDICES}, $response->getIndices());
    }

    private function getData($asObject = false)
    {
        $data = [
            IndexStatsResponse::PROP_OK => true,
            IndexStatsResponse::PROP_ALL => array('all' => array()),
            IndexStatsResponse::PROP_SHARDS => array('total' => array()),
            IndexStatsResponse::PROP_INDICES => array('test-index' => array()),
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }
}

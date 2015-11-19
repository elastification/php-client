<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V1x;

use Elastification\Client\Response\Shared\AbstractBulkResponse;
use Elastification\Client\Response\V1x\BulkResponse;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class BulkResponseTest extends \PHPUnit_Framework_TestCase
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
        $this->nativeArrayGateway = null;
        $this->nativeObjectGateway = null;

        parent::tearDown();
    }

    public function testInstance()
    {
        /** @noinspection PhpParamsInspection */
        $response = new BulkResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V1x\BulkResponse', $response);
    }


    public function testTookArray()
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
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data[BulkResponse::PROP_TOOK], $response->took());
    }

    public function testTookObject()
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
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data->{BulkResponse::PROP_TOOK}, $response->took());
    }

    public function testGetDataArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data, $response->getData());
    }

    public function testGetDataObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data, $response->getData());
    }

    public function testGetRawDataArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data, $response->getRawData());
    }

    public function testGetRawDataObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data, $response->getRawData());
    }

    public function testGetSerializer()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($this->serializer, $response->getSerializer());
    }

    public function testErrorsArray()
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
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data[BulkResponse::PROP_ERRORS], $response->errors());
    }

    public function testErrorsObject()
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
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data->{BulkResponse::PROP_ERRORS}, $response->errors());
    }

    public function testGetItemsArray()
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
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data[BulkResponse::PROP_ITEMS], $response->getItems());
    }

    public function testGetItemsObject()
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
        $response = new BulkResponse($data, $this->serializer);
        $this->assertSame($data->{BulkResponse::PROP_ITEMS}, $response->getItems());
    }

    private function getData($asObject = false)
    {
        $data = [
            BulkResponse::PROP_TOOK => 1,
            BulkResponse::PROP_STATUS => 200,
            BulkResponse::PROP_ERRORS => false,
            BulkResponse::PROP_ITEMS => $this->getItemData($asObject),
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }

    private function getItemData($asObject = false)
    {
        $hits = array(
            'my-action' => array(
                'my' => 'data'
            )
        );

        if ($asObject) {
            return (object)$hits;
        }

        return $hits;
    }
}

<?php

namespace Elastification\Client\Tests\Unit\Response\V1x\Cat;

use Elastification\Client\Response\V1x\Cat\CountCatResponse;

class CountCatResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new CountCatResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Shared\Cat\AbstractCountCatResponse', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V1x\Cat\CountCatResponse', $response);
    }

    public function testGetSerializer()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new CountCatResponse($data, $this->serializer);

        $this->assertSame($this->serializer, $response->getSerializer());
    }

    public function testGetRawData()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new CountCatResponse($data, $this->serializer);
        $this->assertSame($response->getRawData(), $data);
    }

    public function testGetData()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new CountCatResponse($data, $this->serializer);

        $this->assertCount(2, $response->getData());

        foreach($response->getData() as $key => $result) {
            $number = $key + 1;

            $this->assertArrayHasKey(CountCatResponse::PROP_EPOCH, $result);
            $this->assertSame('my-epoch' . $number, $result[CountCatResponse::PROP_EPOCH]);
            $this->assertArrayHasKey(CountCatResponse::PROP_TIMESTAMP, $result);
            $this->assertSame('my-timestamp' . $number, $result[CountCatResponse::PROP_TIMESTAMP]);
            $this->assertArrayHasKey(CountCatResponse::PROP_COUNT, $result);
            $this->assertSame('my-count' . $number, $result[CountCatResponse::PROP_COUNT]);
        }
    }

    private function getData()
    {
        $catRawData = 'my-epoch1 my-timestamp1 my-count1' . PHP_EOL;
        $catRawData .= 'my-epoch2 my-timestamp2 my-count2' . PHP_EOL;

        return $catRawData;
    }
}

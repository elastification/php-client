<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V2x\Cat;

use Elastification\Client\Response\V2x\Cat\AllocationCatResponse;

class AllocationCatResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new AllocationCatResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Shared\Cat\AbstractAllocationCatResponse', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V2x\Cat\AllocationCatResponse', $response);
    }

    public function testGetSerializer()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new AllocationCatResponse($data, $this->serializer);

        $this->assertSame($this->serializer, $response->getSerializer());
    }

    public function testGetRawData()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new AllocationCatResponse($data, $this->serializer);
        $this->assertSame($response->getRawData(), $data);
    }

    public function testGetData()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new AllocationCatResponse($data, $this->serializer);

        $this->assertCount(3, $response->getData());

        foreach($response->getData() as $key => $result) {
            $number = $key + 1;

            $this->assertArrayHasKey(AllocationCatResponse::PROP_SHARDS, $result);
            $this->assertSame('my-shards' . $number, $result[AllocationCatResponse::PROP_SHARDS]);
            $this->assertArrayHasKey(AllocationCatResponse::PROP_DISK_USED, $result);
            $this->assertSame('my-disk-used' . $number, $result[AllocationCatResponse::PROP_DISK_USED]);
            $this->assertArrayHasKey(AllocationCatResponse::PROP_DISK_AVAIL, $result);
            $this->assertSame('my-disk-avail' . $number, $result[AllocationCatResponse::PROP_DISK_AVAIL]);
            $this->assertArrayHasKey(AllocationCatResponse::PROP_DISK_TOTAL, $result);
            $this->assertSame('my-disk-total' . $number, $result[AllocationCatResponse::PROP_DISK_TOTAL]);
            $this->assertArrayHasKey(AllocationCatResponse::PROP_DISK_PERCENT, $result);
            $this->assertSame('my-disk-percent' . $number, $result[AllocationCatResponse::PROP_DISK_PERCENT]);
            $this->assertArrayHasKey(AllocationCatResponse::PROP_HOST, $result);
            $this->assertSame('my-host' . $number, $result[AllocationCatResponse::PROP_HOST]);
            $this->assertArrayHasKey(AllocationCatResponse::PROP_IP, $result);
            $this->assertSame('my-ip' . $number, $result[AllocationCatResponse::PROP_IP]);
            $this->assertArrayHasKey(AllocationCatResponse::PROP_NODE, $result);

            switch($number) {
                case 1:
                    $this->assertSame('my-node' . $number, $result[AllocationCatResponse::PROP_NODE]);
                    break;
                case 2:
                    $this->assertSame('my node' . $number, $result[AllocationCatResponse::PROP_NODE]);
                    break;
                case 3:
                    $this->assertSame('my very great node' . $number, $result[AllocationCatResponse::PROP_NODE]);
                    break;
            }
        }
    }

    private function getData()
    {
        $catRawData = 'my-shards1 my-disk-used1 my-disk-avail1 my-disk-total1 my-disk-percent1 my-host1 my-ip1 my-node1' . PHP_EOL;
        $catRawData .= 'my-shards2 my-disk-used2 my-disk-avail2 my-disk-total2 my-disk-percent2 my-host2 my-ip2 my node2' . PHP_EOL;
        $catRawData .= 'my-shards3 my-disk-used3 my-disk-avail3 my-disk-total3 my-disk-percent3 my-host3 my-ip3 my very great node3' . PHP_EOL;

        return $catRawData;
    }
}

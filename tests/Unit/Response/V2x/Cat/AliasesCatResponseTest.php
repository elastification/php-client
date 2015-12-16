<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V2x\Cat;

use Elastification\Client\Response\V2x\Cat\AliasesCatResponse;

class AliasesCatResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new AliasesCatResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Shared\Cat\AbstractAliasesCatResponse', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V2x\Cat\AliasesCatResponse', $response);
    }

    public function testGetSerializer()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new AliasesCatResponse($data, $this->serializer);

        $this->assertSame($this->serializer, $response->getSerializer());
    }

    public function testGetRawData()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new AliasesCatResponse($data, $this->serializer);
        $this->assertSame($response->getRawData(), $data);
    }

    public function testGetData()
    {
        $data = $this->getData();

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new AliasesCatResponse($data, $this->serializer);

        $this->assertCount(2, $response->getData());

        foreach($response->getData() as $key => $result) {
            $number = $key + 1;

            $this->assertArrayHasKey(AliasesCatResponse::PROP_ALIAS, $result);
            $this->assertSame('my-alias' . $number, $result[AliasesCatResponse::PROP_ALIAS]);
            $this->assertArrayHasKey(AliasesCatResponse::PROP_INDEX, $result);
            $this->assertSame('my-index' . $number, $result[AliasesCatResponse::PROP_INDEX]);
            $this->assertArrayHasKey(AliasesCatResponse::PROP_FILTER, $result);
            $this->assertSame('my-filter' . $number, $result[AliasesCatResponse::PROP_FILTER]);
            $this->assertArrayHasKey(AliasesCatResponse::PROP_ROUTING_INDEX, $result);
            $this->assertSame('my-routing-index' . $number, $result[AliasesCatResponse::PROP_ROUTING_INDEX]);
            $this->assertArrayHasKey(AliasesCatResponse::PROP_ROUTING_SEARCH, $result);
            $this->assertSame('my-routing-search' . $number, $result[AliasesCatResponse::PROP_ROUTING_SEARCH]);
        }
    }

    private function getData()
    {
        $catRawData = 'my-alias1 my-index1 my-filter1 my-routing-index1 my-routing-search1' . PHP_EOL;
        $catRawData .= 'my-alias2 my-index2 my-filter2 my-routing-index2 my-routing-search2' . PHP_EOL;

        return $catRawData;
    }
}

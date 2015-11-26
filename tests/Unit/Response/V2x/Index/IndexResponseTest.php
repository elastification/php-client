<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V2x\Index;

use Elastification\Client\Response\V2x\Index\IndexResponse;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class CreateIndexResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new IndexResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V2x\Index\IndexResponse', $response);
    }


    public function testAcknowlegedArray()
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
        $response = new IndexResponse($data, $this->serializer);
        $this->assertSame($data[IndexResponse::PROP_ACKNOWLEDGED], $response->acknowledged());
    }

    public function testAcknowlegedObject()
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
        $response = new IndexResponse($data, $this->serializer);
        $this->assertSame($data->{IndexResponse::PROP_ACKNOWLEDGED}, $response->acknowledged());
    }

    private function getData($asObject = false)
    {
        $data = [
            IndexResponse::PROP_ACKNOWLEDGED => true,
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }
}

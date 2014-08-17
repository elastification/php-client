<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response;

use Elastification\Client\Response\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new Response('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
    }

    public function testGetSerializer()
    {
        /** @noinspection PhpParamsInspection */
        $response = new Response('data', $this->serializer);
        $this->assertSame($this->serializer, $response->getSerializer());
    }

    public function testGetRawData()
    {
        $data = 'raw data';

        /** @noinspection PhpParamsInspection */
        $response = new Response($data, $this->serializer);
        $this->assertSame($data, $response->getRawData());
    }

    public function testGetData()
    {
        $data = 'raw data';

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new Response($data, $this->serializer);
        $this->assertSame($data, $response->getData());
    }

    public function testGetDataTwice()
    {
        $data = 'raw data';

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new Response($data, $this->serializer);
        $this->assertSame($data, $response->getData());
        $this->assertSame($data, $response->getData());
    }

    public function testGetDataWithNull()
    {
        $data = null;

        $this->serializer->expects($this->never())
            ->method('deserialize');

        /** @noinspection PhpParamsInspection */
        $response = new Response($data, $this->serializer);
        $this->assertNull($response->getData());
    }

}

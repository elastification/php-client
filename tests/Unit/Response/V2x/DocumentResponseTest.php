<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V2x;

use Elastification\Client\Exception\ResponseException;
use Elastification\Client\Response\V2x\DocumentResponse;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class DocumentResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new DocumentResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V2x\DocumentResponse', $response);
    }

    public function testHasSourceArray()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertTrue($response->hasSource());
    }

    public function testHasSourceObject()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertTrue($response->hasSource());
    }

    public function testFoundArray()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertTrue($response->found());
    }

    public function testFoundObject()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertTrue($response->found());
    }

    public function testGetSourceArray()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertEquals($data[DocumentResponse::PROP_SOURCE], $response->getSource());
    }

    public function testGetSourceObject()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertEquals($data->{DocumentResponse::PROP_SOURCE}, $response->getSource());
    }

    public function testGetSourceException()
    {
        $data = $this->getData();
        unset($data[DocumentResponse::PROP_SOURCE]);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue(new NativeArrayGateway($data)));

        /** @noinspection PhpParamsInspection */
        $response = new DocumentResponse($data, $this->serializer);

        try {
            $response->getSource();
        } catch (ResponseException $exception) {
            $this->assertSame('_source is not set.', $exception->getMessage());
            return;
        }

        $this->fail();
    }

    public function testGetIdArray()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data[DocumentResponse::PROP_ID], $response->getId());
    }

    public function testGetIdObject()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DocumentResponse::PROP_ID}, $response->getId());
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data[DocumentResponse::PROP_VERSION], $response->getVersion());
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DocumentResponse::PROP_VERSION}, $response->getVersion());
    }

    public function testGetIndexArray()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data[DocumentResponse::PROP_INDEX], $response->getIndex());
    }

    public function testGetIndexObject()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DocumentResponse::PROP_INDEX}, $response->getIndex());
    }

    public function testGetTypeArray()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data[DocumentResponse::PROP_TYPE], $response->getType());
    }

    public function testGetTypeObject()
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
        $response = new DocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DocumentResponse::PROP_TYPE}, $response->getType());
    }

    private function getData($asObject = false)
    {
        $data = [
            DocumentResponse::PROP_FOUND => true,
            DocumentResponse::PROP_ID => '123asd',
            DocumentResponse::PROP_VERSION => 2,
            DocumentResponse::PROP_INDEX => 'test-index',
            DocumentResponse::PROP_TYPE => 'test-type',
            DocumentResponse::PROP_SOURCE => [
                'data' => 'source'
            ]
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }
}

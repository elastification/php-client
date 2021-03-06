<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V1x;

use Elastification\Client\Response\V1x\CreateUpdateDocumentResponse;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class CreateUpdateDocumentResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new CreateUpdateDocumentResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V1x\CreateUpdateDocumentResponse', $response);
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_ID], $response->getId());
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_ID}, $response->getId());
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_VERSION], $response->getVersion());
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_VERSION}, $response->getVersion());
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_INDEX], $response->getIndex());
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_INDEX}, $response->getIndex());
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_TYPE], $response->getType());
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_TYPE}, $response->getType());
    }

    public function testCreatedArray()
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_CREATED], $response->created());
    }

    public function testCreatedObject()
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
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_CREATED}, $response->created());
    }

    private function getData($asObject = false)
    {
        $data = [
            CreateUpdateDocumentResponse::PROP_CREATED => true,
            CreateUpdateDocumentResponse::PROP_ID => '123asd',
            CreateUpdateDocumentResponse::PROP_VERSION => 2,
            CreateUpdateDocumentResponse::PROP_INDEX => 'test-index',
            CreateUpdateDocumentResponse::PROP_TYPE => 'test-type',
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }
}

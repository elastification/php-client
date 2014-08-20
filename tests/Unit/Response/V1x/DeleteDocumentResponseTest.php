<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V1x;

use Elastification\Client\Response\V1x\DeleteDocumentResponse;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class DeleteDocumentResponseTest extends \PHPUnit_Framework_TestCase
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
        $response = new DeleteDocumentResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V1x\DeleteDocumentResponse', $response);
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data[DeleteDocumentResponse::PROP_ID], $response->getId());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DeleteDocumentResponse::PROP_ID}, $response->getId());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data[DeleteDocumentResponse::PROP_VERSION], $response->getVersion());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DeleteDocumentResponse::PROP_VERSION}, $response->getVersion());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data[DeleteDocumentResponse::PROP_INDEX], $response->getIndex());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DeleteDocumentResponse::PROP_INDEX}, $response->getIndex());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data[DeleteDocumentResponse::PROP_TYPE], $response->getType());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DeleteDocumentResponse::PROP_TYPE}, $response->getType());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data[DeleteDocumentResponse::PROP_OK], $response->isOk());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DeleteDocumentResponse::PROP_OK}, $response->isOk());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data[DeleteDocumentResponse::PROP_FOUND], $response->found());
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
        $response = new DeleteDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{DeleteDocumentResponse::PROP_FOUND}, $response->found());
    }

    private function getData($asObject = false)
    {
        $data = [
            DeleteDocumentResponse::PROP_OK => true,
            DeleteDocumentResponse::PROP_FOUND => true,
            DeleteDocumentResponse::PROP_ID => '123asd',
            DeleteDocumentResponse::PROP_VERSION => 2,
            DeleteDocumentResponse::PROP_INDEX => 'test-index',
            DeleteDocumentResponse::PROP_TYPE => 'test-type',
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }
}

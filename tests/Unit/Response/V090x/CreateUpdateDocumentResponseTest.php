<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V090x;

use Elastification\Client\Response\V090x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V090x\DeleteDocumentResponse;

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
        $this->assertInstanceOf('Elastification\Client\Response\V090x\CreateUpdateDocumentResponse', $response);
    }

    public function testGetIdArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_ID], $response->getId());
    }

    public function testGetIdObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo([]))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_ID}, $response->getId());
    }

    public function testGetVersionArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_VERSION], $response->getVersion());
    }

    public function testGetVersionObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_VERSION}, $response->getVersion());
    }

    public function testGetIndexArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_INDEX], $response->getIndex());
    }

    public function testGetIndexObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_INDEX}, $response->getIndex());
    }

    public function testGetTypeArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_TYPE], $response->getType());
    }

    public function testGetTypeObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_TYPE}, $response->getType());
    }

    public function testIsOkArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data[CreateUpdateDocumentResponse::PROP_OK], $response->isOk());
    }

    public function testIsOkObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($data),
                $this->equalTo(array()))
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new CreateUpdateDocumentResponse($data, $this->serializer);
        $this->assertSame($data->{CreateUpdateDocumentResponse::PROP_OK}, $response->isOk());
    }

    private function getData($asObject = false)
    {
        $data = [
            CreateUpdateDocumentResponse::PROP_OK => true,
            CreateUpdateDocumentResponse::PROP_ID => '123asd',
            CreateUpdateDocumentResponse::PROP_VERSION => 2,
            CreateUpdateDocumentResponse::PROP_INDEX => 'test-index',
            CreateUpdateDocumentResponse::PROP_TYPE => 'test-type',
        ];

        if($asObject) {
            return (object) $data;
        }

        return $data;
    }
}
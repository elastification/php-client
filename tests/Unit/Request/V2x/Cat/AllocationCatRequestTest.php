<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V2x\Cat;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\Cat\AbstractAllocationCatRequest;
use Elastification\Client\Request\V2x\Cat\AllocationCatRequest;

class AllocationCatRequestTest extends \PHPUnit_Framework_TestCase
{
    const RESPONSE_CLASS = 'Elastification\Client\Response\Response';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var AllocationCatRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new AllocationCatRequest(null, null, $this->serializer);
    }

    protected function tearDown()
    {
        $this->serializer = null;
        $this->request = null;

        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'Elastification\Client\Request\RequestInterface',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\Shared\Cat\AbstractAllocationCatRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V2x\Cat\AllocationCatRequest',
            $this->request
        );
    }

    public function testGetIndex()
    {
        $this->assertSame(AbstractAllocationCatRequest::REQUEST_ACTION, $this->request->getIndex());
    }

    public function testGetType()
    {
        $this->assertNull($this->request->getType());
    }

    public function testGetMethod()
    {
        $this->assertSame(RequestMethods::GET, $this->request->getMethod());
    }

    public function testGetAction()
    {
        $this->assertSame(AbstractAllocationCatRequest::CAT_TYPE, $this->request->getAction());
    }
//
    public function testGetSerializer()
    {
        $this->assertSame($this->serializer, $this->request->getSerializer());
    }

    public function testGetSerializerParams()
    {
        $this->assertTrue(is_array($this->request->getSerializerParams()));
        $this->assertEmpty($this->request->getSerializerParams());
    }

    public function testSetGetBody()
    {
        $body = 'my test body';

        $this->serializer->expects($this->never())
            ->method('serialize');

        $this->request->setBody($body);

        $this->assertNull($this->request->getBody());
    }

    public function testGetSupportedClass()
    {
        $this->assertSame(self::RESPONSE_CLASS, $this->request->getSupportedClass());
    }

    public function testCreateResponse()
    {
        $rawData = 'raw data for testing';
        $response = $this->request->createResponse($rawData, $this->serializer);

        $this->assertInstanceOf(self::RESPONSE_CLASS, $response);
    }

    public function testGetParameters()
    {
        $parameters = $this->request->getParameters();

        $this->assertArrayHasKey('format', $parameters);
        $this->assertSame('json', $parameters['format']);
    }
}

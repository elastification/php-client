<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 15/08/14
 * Time: 08:35
 */
namespace Elastification\Client\Tests\Unit\Request\V1x\Index;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\V1x\Index\GetMappingRequest;

class GetMappingRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const TYPE = 'test-type';
    const RESPONSE_CLASS = 'Elastification\Client\Response\Response';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var GetMappingRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        /** @noinspection PhpParamsInspection */
        $this->request = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);
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
            'Elastification\Client\Request\Shared\Index\AbstractGetMappingRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V1x\Index\GetMappingRequest',
            $this->request
        );
    }

    public function testGetIndex()
    {
        $this->assertSame(self::INDEX, $this->request->getIndex());
    }

    public function testGetType()
    {
        $this->assertSame(self::TYPE, $this->request->getType());
    }

    public function testGetMethod()
    {
        $this->assertSame(RequestMethods::GET, $this->request->getMethod());
    }

    public function testGetAction()
    {
        $this->assertSame(GetMappingRequest::REQUEST_ACTION, $this->request->getAction());
    }

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
        /** @noinspection PhpParamsInspection */
        $response = $this->request->createResponse($rawData, $this->serializer);

        $this->assertInstanceOf(self::RESPONSE_CLASS, $response);
    }

}

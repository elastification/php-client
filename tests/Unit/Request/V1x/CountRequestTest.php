<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V1x;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\V1x\CountRequest;

class CountRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const TYPE = 'test-type';
    const RESPONSE_CLASS = 'Elastification\Client\Response\V1x\CountResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var CountRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new CountRequest(self::INDEX, self::TYPE, $this->serializer);
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
            'Elastification\Client\Request\Shared\AbstractCountRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V1x\CountRequest',
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
        $this->assertSame(RequestMethods::POST, $this->request->getMethod());
    }

    public function testGetAction()
    {
        $this->assertSame(CountRequest::REQUEST_ACTION, $this->request->getAction());
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

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with(
                $this->equalTo($body),
                $this->equalTo(array())
            )
            ->will($this->returnValue($body));

        $this->request->setBody($body);

        $this->assertSame($body, $this->request->getBody());
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

    public function testConstructorWithJmsSerializer()
    {
        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\JmsSerializer')
            ->disableOriginalConstructor()
            ->getMock();

        $request = new CountRequest(self::INDEX, self::TYPE, $this->serializer);

        $params = $request->getSerializerParams();
        $this->assertSame($params['index'], self::INDEX);
        $this->assertSame($params['type'], self::TYPE);
    }

}

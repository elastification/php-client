<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V1x;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\V1x\CreateTemplateRequest;

class CreateTemplateRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-template';
    const RESPONSE_CLASS = 'Elastification\Client\Response\V1x\Index\IndexResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var CreateTemplateRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        /** @noinspection PhpParamsInspection */
        $this->request = new CreateTemplateRequest(self::INDEX, $this->serializer);
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
            'Elastification\Client\Request\Shared\AbstractCreateTemplateRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V1x\CreateTemplateRequest',
            $this->request
        );
    }

    public function testGetIndex()
    {
        $this->assertSame(CreateTemplateRequest::REQUEST_ACTION, $this->request->getIndex());
    }

    public function testGetType()
    {
        $this->assertSame(self::INDEX, $this->request->getType());
    }

    public function testSetTemplateName()
    {
        $templateName = 'new-name';
        $this->request->setTemplateName($templateName);
        $this->assertSame($templateName, $this->request->getType());
    }

    public function testGetMethod()
    {
        $this->assertSame(RequestMethods::PUT, $this->request->getMethod());
    }

    public function testGetAction()
    {
        $this->assertNull($this->request->getAction());
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
        /** @noinspection PhpParamsInspection */
        $response = $this->request->createResponse($rawData, $this->serializer);

        $this->assertInstanceOf(self::RESPONSE_CLASS, $response);
    }

}

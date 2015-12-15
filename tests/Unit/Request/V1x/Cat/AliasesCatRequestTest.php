<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V1x\Cat;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\Cat\AbstractAliasesCatRequest;
use Elastification\Client\Request\V1x\Cat\AliasesCatRequest;
use Elastification\Client\Request\V1x\CountRequest;

class AliasesCatRequestTest extends \PHPUnit_Framework_TestCase
{
    const RESPONSE_CLASS = 'Elastification\Client\Response\V1x\Cat\AliasesCatResponse';

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

        $this->request = new AliasesCatRequest(null, null, $this->serializer);
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
            'Elastification\Client\Request\Shared\Cat\AbstractAliasesCatRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V1x\Cat\AliasesCatRequest',
            $this->request
        );
    }

    public function testGetIndex()
    {
        $this->assertSame(AbstractAliasesCatRequest::REQUEST_ACTION, $this->request->getIndex());
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
        $this->assertSame(AbstractAliasesCatRequest::CAT_TYPE, $this->request->getAction());
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
}

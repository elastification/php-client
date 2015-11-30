<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V2x\Index;

use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\V2x\Index\DeleteWarmerRequest;

class DeleteWarmerRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const RESPONSE_CLASS = 'Elastification\Client\Response\V2x\Index\IndexResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var DeleteWarmerRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        /** @noinspection PhpParamsInspection */
        $this->request = new DeleteWarmerRequest(self::INDEX, null, $this->serializer);
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
            'Elastification\Client\Request\Shared\Index\AbstractDeleteWarmerRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V2x\Index\DeleteWarmerRequest',
            $this->request
        );
    }

    public function testGetIndex()
    {
        $this->assertSame(self::INDEX, $this->request->getIndex());
    }

    public function testGetType()
    {
        $this->assertNull($this->request->getType());
    }

    public function testGetMethod()
    {
        $this->assertSame(RequestMethods::DELETE, $this->request->getMethod());
    }

    public function testGetAction()
    {
        $warmerName = 'test-warmer-name';
        $this->request->setWarmerName($warmerName);
        $this->assertSame(DeleteWarmerRequest::REQUEST_ACTION . '/' . $warmerName, $this->request->getAction());
    }

    public function testGetActionException()
    {
        try {
            $this->request->getAction();
        } catch(RequestException $exception) {
            $this->assertSame('Warmer name is not set', $exception->getMessage());
            return;
        }

        $this->fail();
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

    public function testGetSetWarmerName()
    {
        $warmerName = 'test-warmer-name';

        $this->assertNull($this->request->getWarmerName());
        $this->request->setWarmerName($warmerName);
        $this->assertSame($warmerName, $this->request->getWarmerName());
    }

}

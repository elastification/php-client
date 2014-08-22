<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V090x;

use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\V090x\GetDocumentRequest;

class GetDocumentRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const TYPE = 'test-type';
    const RESPONSE_CLASS = 'Elastification\Client\Response\V090x\DocumentResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var GetDocumentRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
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
            'Elastification\Client\Request\Shared\AbstractGetDocumentRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V090x\GetDocumentRequest',
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

    public function testSetIdGetAction()
    {
        $id = 'my document id';
        $this->request->setId($id);

        $this->assertSame($id, $this->request->getAction());
    }

    public function testGetActionException()
    {
        try {
            $this->request->getAction();
        } catch (RequestException $exception) {
            $this->assertSame('id can not be empty for this request', $exception->getMessage());
            return;
        }

        $this->fail();
    }

    public function testSetIdException()
    {
        $id = '';

        try {
            $this->request->setId($id);
        } catch (RequestException $exception) {
            $this->assertSame('Id can not be empty', $exception->getMessage());
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
        $response = $this->request->createResponse($rawData, $this->serializer);

        $this->assertInstanceOf(self::RESPONSE_CLASS, $response);
    }

    public function testConstructorWithJmsSerializer()
    {
        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\JmsSerializer')
            ->disableOriginalConstructor()
            ->getMock();

        $request = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);

        $params = $request->getSerializerParams();
        $this->assertSame($params['index'], self::INDEX);
        $this->assertSame($params['type'], self::TYPE);
    }

}

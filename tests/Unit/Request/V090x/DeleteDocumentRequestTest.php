<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Dawen\Component\Elastic\Tests\Unit\Serializer;

use Dawen\Component\Elastic\Exception\RequestException;
use Dawen\Component\Elastic\Request\RequestMethods;
use Dawen\Component\Elastic\Request\V090x\DeleteDocumentRequest;

class DeleteDocumentRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const TYPE = 'test-type';
    const RESPONSE_CLASS = 'Dawen\Component\Elastic\Response\V090x\DeleteDocumentResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var DeleteDocumentRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Dawen\Component\Elastic\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new DeleteDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
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
            'Dawen\Component\Elastic\Request\RequestInterface',
            $this->request);

        $this->assertInstanceOf(
            'Dawen\Component\Elastic\Request\Shared\AbstractDeleteDocumentRequest',
            $this->request);

        $this->assertInstanceOf(
            'Dawen\Component\Elastic\Request\V090x\DeleteDocumentRequest',
            $this->request);
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
        $this->assertSame(RequestMethods::DELETE, $this->request->getMethod());
    }

    public function testSetIdGetAction()
    {
        $id = 'my document id';
        $this->request->setId($id);

        $this->assertSame($id, $this->request->getAction());
    }

    public function testSetIdException()
    {
        $id = '';

        try {
            $this->request->setId($id);
        } catch(RequestException $exception) {
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

}
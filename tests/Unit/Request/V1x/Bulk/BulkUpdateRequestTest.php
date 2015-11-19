<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V1x\Bulk;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\Bulk\AbstractBulkUpdateRequest;
use Elastification\Client\Request\V1x\Bulk\BulkUpdateRequest;

class BulkUpdateRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const TYPE = 'test-type';
    const RESPONSE_CLASS = 'Elastification\Client\Response\V1x\BulkResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var BulkUpdateRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        /** @noinspection PhpParamsInspection */
        $this->request = new BulkUpdateRequest(self::INDEX, self::TYPE, $this->serializer);
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
            'Elastification\Client\Request\Shared\Bulk\AbstractBulkUpdateRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V1x\Bulk\BulkUpdateRequest',
            $this->request
        );
    }

    public function testGetIndex()
    {
        $this->assertNull($this->request->getIndex());
    }

    public function testGetType()
    {
        $this->assertNull($this->request->getType());
    }

    public function testGetMethod()
    {
        $this->assertSame(RequestMethods::POST, $this->request->getMethod());
    }

    public function testGetAction()
    {
        $this->assertSame(BulkUpdateRequest::REQUEST_ACTION, $this->request->getAction());
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

    public function testAddAction()
    {
        $id = 'my-id';
        $doc = array('my' => 'doc');
        $serializedDoc = 'my-doc';
        $action = array(
            AbstractBulkUpdateRequest::BULK_ACTION => array(
                '_id' => $id,
                '_index' => self::INDEX,
                '_type' => self::TYPE
            )
        );

        $this->serializer->expects($this->once())->method('serialize')->with($doc, array())->willReturn($serializedDoc);

        $this->request->addDocument($doc, $id);

        $expected = json_encode($action) .
            AbstractBulkUpdateRequest::LINE_BREAK .
            '{"doc":' . $serializedDoc . '}' .
            AbstractBulkUpdateRequest::LINE_BREAK;

        $result = $this->request->getBody();

        $this->assertSame($expected, $result);
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V090x\Bulk;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\Bulk\AbstractBulkDeleteRequest;
use Elastification\Client\Request\V090x\Bulk\BulkCreateRequest;
use Elastification\Client\Request\V090x\Bulk\BulkDeleteRequest;

class BulkDeleteRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const TYPE = 'test-type';
    const RESPONSE_CLASS = 'Elastification\Client\Response\V090x\BulkResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var BulkDeleteRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        /** @noinspection PhpParamsInspection */
        $this->request = new BulkDeleteRequest(self::INDEX, self::TYPE, $this->serializer);
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
            'Elastification\Client\Request\Shared\Bulk\AbstractBulkDeleteRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V090x\Bulk\BulkDeleteRequest',
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
        $this->assertSame(BulkCreateRequest::REQUEST_ACTION, $this->request->getAction());
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
        $id1 = 'my-id1';
        $id2 = 'my-id2';

        $action1 = array(
            AbstractBulkDeleteRequest::BULK_ACTION => array(
                '_id' => $id1,
                '_index' => self::INDEX,
                '_type' => self::TYPE
            )
        );

        $action2 = array(
            AbstractBulkDeleteRequest::BULK_ACTION => array(
                '_id' => $id2,
                '_index' => self::INDEX,
                '_type' => self::TYPE
            )
        );

        $this->serializer->expects($this->never())->method('serialize');

        $this->request->add(array($id1, $id2));

        $expected = json_encode($action1) .
            AbstractBulkDeleteRequest::LINE_BREAK .
            json_encode($action2) .
            AbstractBulkDeleteRequest::LINE_BREAK;

        $result = $this->request->getBody();

        $this->assertSame($expected, $result);
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Request\V2x;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\AbstractSearchScrollRequest;
use Elastification\Client\Request\V2x\SearchScrollRequest;

class SearchScrollRequestTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'test-index';
    const TYPE = 'test-type';
    const RESPONSE_CLASS = 'Elastification\Client\Response\V2x\SearchResponse';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var SearchScrollRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new SearchScrollRequest(self::INDEX, self::TYPE, $this->serializer);
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
            'Elastification\Client\Request\Shared\AbstractSearchScrollRequest',
            $this->request
        );

        $this->assertInstanceOf(
            'Elastification\Client\Request\V2x\SearchScrollRequest',
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
        $this->assertSame(RequestMethods::GET, $this->request->getMethod());
    }

    public function testGetAction()
    {
        $this->assertSame(SearchScrollRequest::REQUEST_ACTION, $this->request->getAction());
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

    public function testSetGetParameters()
    {
        $name = 'my_param';
        $value = 555;

        $this->request->setParameter($name, $value);

        $parameters = $this->request->getParameters();
        $this->assertArrayHasKey($name, $parameters);
        $this->assertEquals((string) $value, $parameters[$name]);
        $this->assertTrue(is_string($parameters[$name]));
    }

    public function testSetScroll()
    {
        $scroll = '5m';

        $this->request->setScroll($scroll);
        $params = $this->request->getParameters();

        $this->assertArrayHasKey(AbstractSearchScrollRequest::PARAM_SCROLL, $params);
        $this->assertEquals($scroll, $params[AbstractSearchScrollRequest::PARAM_SCROLL]);
    }

    public function testSetScrollId()
    {
        $scrollId = 'myIdFroScrolledSearch';

        $this->request->setScrollId($scrollId);
        $params = $this->request->getParameters();

        $this->assertArrayHasKey(AbstractSearchScrollRequest::PARAM_SCROLL_ID, $params);
        $this->assertEquals($scrollId, $params[AbstractSearchScrollRequest::PARAM_SCROLL_ID]);
    }

}

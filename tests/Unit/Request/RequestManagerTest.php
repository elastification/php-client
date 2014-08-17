<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Response;

use Elastification\Client\Exception\RequestManagerException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;

class RequestManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  RequestManagerInterface */
    private $requestManager;

    protected function setUp()
    {
        parent::setUp();
        $this->requestManager = new RequestManager();
    }

    protected function tearDown()
    {
        $this->requestManager = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\Request\RequestManagerInterface', $this->requestManager);
        $this->assertInstanceOf('Elastification\Client\Request\RequestManager', $this->requestManager);
    }

    public function testHasRequest()
    {
        $this->assertFalse($this->requestManager->hasRequest('test.name'));
    }

    public function testSetHasRequest()
    {
        $name = 'test.request';
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')->getMock();

        /** @noinspection PhpParamsInspection */
        $this->requestManager->setRequest($name, $request);
        $this->assertTrue($this->requestManager->hasRequest($name));

    }

    public function testSetHasRequestMultiple()
    {
        $name1 = 'test.request1';
        $name2 = 'test.request2';
        $name3 = 'test.request3';
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')->getMock();

        /** @noinspection PhpParamsInspection */
        $this->requestManager->setRequest($name1, $request);
        /** @noinspection PhpParamsInspection */
        $this->requestManager->setRequest($name2, $request);
        /** @noinspection PhpParamsInspection */
        $this->requestManager->setRequest($name3, $request);
        $this->assertTrue($this->requestManager->hasRequest($name1));
        $this->assertTrue($this->requestManager->hasRequest($name2));
        $this->assertTrue($this->requestManager->hasRequest($name3));

    }

    public function testSetRequestException()
    {
        $name = 'test.request';
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')->getMock();

        try {
            /** @noinspection PhpParamsInspection */
            $this->requestManager->setRequest($name, $request);
            /** @noinspection PhpParamsInspection */
            $this->requestManager->setRequest($name, $request);
        } catch (RequestManagerException $exception) {
            $this->assertSame('a request for "' . $name . '" is already registered', $exception->getMessage());
            return;
        }

        $this->fail();
    }

    public function testGetRequestNull()
    {
        $name = 'test.request';

        $this->assertNull($this->requestManager->getRequest($name));
    }

    public function testGetRequest()
    {
        $name = 'test.request';
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')->getMock();

        /** @noinspection PhpParamsInspection */
        $this->requestManager->setRequest($name, $request);
        $this->assertSame($request, $this->requestManager->getRequest($name));
    }

    public function testRemoveRequestNotExisting()
    {
        $name = 'test.request';
        $this->assertFalse($this->requestManager->removeRequest($name));
    }

    public function testRemoveRequest()
    {
        $name = 'test.request';
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')->getMock();

        /** @noinspection PhpParamsInspection */
        $this->requestManager->setRequest($name, $request);

        $this->assertTrue($this->requestManager->hasRequest($name));
        $this->assertTrue($this->requestManager->removeRequest($name));
        $this->assertFalse($this->requestManager->hasRequest($name));
    }

    public function testReset()
    {
        $name = 'test.request';
        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')->getMock();

        /** @noinspection PhpParamsInspection */
        $this->requestManager->setRequest($name, $request);

        $this->assertTrue($this->requestManager->hasRequest($name));
        $this->requestManager->reset($name);
        $this->assertFalse($this->requestManager->hasRequest($name));
    }

}

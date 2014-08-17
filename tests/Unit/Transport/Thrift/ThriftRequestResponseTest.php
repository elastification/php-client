<?php
namespace Elastification\Client\Tests\Fixtures\Unit\Transport\Thrift;

use Elastification\Client\Transport\Thrift\ThriftRequest;
use Elastification\Client\Transport\Thrift\ThriftResponse;

class ThriftRequestResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testSetterMethodsArePipedToWrappedRequest()
    {
        $fixtureBody = 'test body';
        $fixturePath = '/some/index';
        $fixtureQuery = ['size' => 5];

        $thriftRequest = new ThriftRequest('GET');
        $thriftRequest->setBody($fixtureBody);
        $thriftRequest->setPath($fixturePath);
        $thriftRequest->setQueryParams($fixtureQuery);

        $wrapped = $thriftRequest->getWrappedRequest();
        $this->assertInstanceOf('Elasticsearch\RestRequest', $wrapped);

        $this->assertEquals($fixtureBody, $wrapped->body);
        $this->assertEquals($fixturePath, $wrapped->uri);
        $this->assertEquals($fixtureQuery, $wrapped->parameters);
    }

    public function testResponse()
    {
        $testResponseBody = 'test';
        $restResponse = $this->getMockBuilder('Elasticsearch\RestResponse')
                             ->disableOriginalConstructor()
                             ->getMock();

        // Seems to be the only way. Assertions on a mocked property are quite hard.
        $restResponse->body = $testResponseBody;

        $thriftResponse = new ThriftResponse($restResponse);

        $this->assertEquals($testResponseBody, $thriftResponse->getBody());
    }
}


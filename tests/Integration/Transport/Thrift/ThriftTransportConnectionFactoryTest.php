<?php
namespace Elastification\Client\Tests\Fixtures\Unit\Transport\Thrift;

use Elastification\Client\Transport\Thrift\ThriftTransportConnectionFactory;

class ThriftTransportConnectionFactoryTest extends \PHPUnit_Framework_TestCase
{

    private function guardExtensionPresent()
    {
        if (!extension_loaded('thrift_protocol')) {
            $this->markTestSkipped('Thrift Extension not installed, skipping test.');
        }
    }


    public function testFactoryDefaultParams()
    {
        $this->guardExtensionPresent();
        $restClient = ThriftTransportConnectionFactory::factory('localhost', 9500);
        $this->assertInstanceOf('Elasticsearch\RestClient', $restClient);
    }

    public function testFactoryWithSendTimeout()
    {
        $this->guardExtensionPresent();
        $restClient = ThriftTransportConnectionFactory::factory('localhost', 9500, 100);
        $this->assertInstanceOf('Elasticsearch\RestClient', $restClient);
    }

    public function testFactoryWithReceiveTimeout()
    {
        $this->guardExtensionPresent();
        $restClient = ThriftTransportConnectionFactory::factory('localhost', 9500, null, 100);
        $this->assertInstanceOf('Elasticsearch\RestClient', $restClient);
    }

    public function testFactoryWithBothTimeouts()
    {
        $this->guardExtensionPresent();
        $restClient = ThriftTransportConnectionFactory::factory('localhost', 9500, 100, 100);
        $this->assertInstanceOf('Elasticsearch\RestClient', $restClient);
    }

    public function testFactoryWithBothTimeoutsAndFramedTransport()
    {
        $this->guardExtensionPresent();
        $restClient = ThriftTransportConnectionFactory::factory('localhost', 9500, 100, 100, true);
        $this->assertInstanceOf('Elasticsearch\RestClient', $restClient);
    }
}


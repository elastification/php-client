<?php
namespace Elastification\Client\Serializer\Gateway;

class GatewayFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldBeObjectGateway()
    {
        $fixture = ['a' => 'b'];
        $val = GatewayFactory::factory($fixture);
        $this->assertInstanceOf(
            'Elastification\Client\Serializer\Gateway\NativeArrayGateway',
            $val
        );
    }

    public function testShouldBeArrayGateway()
    {
        $fixture = new \stdClass();
        $fixture->a = 'b';
        $val = GatewayFactory::factory($fixture);
        $this->assertInstanceOf(
            'Elastification\Client\Serializer\Gateway\NativeObjectGateway',
            $val
        );
    }

    public function testShouldBeScalar()
    {
        $fixture = 4711;
        $val = GatewayFactory::factory($fixture);
        $this->assertSame($fixture, $val);
    }
}


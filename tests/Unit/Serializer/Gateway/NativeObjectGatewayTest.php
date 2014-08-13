<?php
namespace Elastification\Client\Serializer\Gateway;

class NativeObjectGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function testScalarAccessWorksProperly()
    {
        $fixture = new \stdClass();
        $fixture->test = 'value';
        $subject = new NativeObjectGateway($fixture);
        $this->assertEquals('value', $subject['test']);
    }

    public function testNestedObjectAccessWorksProperly()
    {
        $fixture = new \stdClass();
        $fixture->test = new \stdClass();
        $fixture->test->sub = 'value';
        $subject = new NativeObjectGateway($fixture);
        $nested = $subject['test'];
        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeObjectGateway', $nested);
        $this->assertEquals('value', $nested['sub']);
    }

    public function testReturnsNullOnNonExistentProperty()
    {
        $fixture = new \stdClass();
        $subject = new NativeObjectGateway($fixture);
        $value = $subject['non_existent'];
        $this->assertNull($value);
    }
}


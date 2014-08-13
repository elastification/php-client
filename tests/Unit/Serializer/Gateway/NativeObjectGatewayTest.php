<?php
namespace Dawen\Component\Elastic\Serializer\Gateway;

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
        $this->assertInstanceOf('Dawen\Component\Elastic\Serializer\Gateway\NativeObjectGateway', $nested);
        $this->assertEquals('value', $nested['sub']);
    }
}


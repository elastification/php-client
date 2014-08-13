<?php
namespace Dawen\Component\Elastic\Serializer\Gateway;

class NativeJsonGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function testScalarAccessWorksProperly()
    {
        $fixture = ['test' => 'value'];
        $subject = new NativeJsonGateway($fixture);
        $this->assertEquals('value', $subject['test']);
    }

    public function testNestedArrayAccessWorksProperly()
    {
        $fixture = ['test' => ['sub' => 'value']];
        $subject = new NativeJsonGateway($fixture);
        $nested = $subject['test'];
        $this->assertInstanceOf('Dawen\Component\Elastic\Serializer\Gateway\NativeJsonGateway', $nested);
        $this->assertEquals('value', $nested['sub']);
    }
}


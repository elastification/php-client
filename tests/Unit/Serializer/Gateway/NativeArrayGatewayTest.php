<?php
namespace Dawen\Component\Elastic\Serializer\Gateway;

class NativeArrayGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function testScalarAccessWorksProperly()
    {
        $fixture = ['test' => 'value'];
        $subject = new NativeArrayGateway($fixture);
        $this->assertEquals('value', $subject['test']);
    }

    public function testNestedArrayAccessWorksProperly()
    {
        $fixture = ['test' => ['sub' => 'value']];
        $subject = new NativeArrayGateway($fixture);
        $nested = $subject['test'];
        $this->assertInstanceOf('Dawen\Component\Elastic\Serializer\Gateway\NativeArrayGateway', $nested);
        $this->assertEquals('value', $nested['sub']);
    }
}


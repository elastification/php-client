<?php
namespace Elastification\Client\Serializer\Gateway;

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
        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeArrayGateway', $nested);
        $this->assertEquals('value', $nested['sub']);
    }

    public function testUnsetIndexReturnsNull()
    {
        $fixture = ['test' => ['sub' => 'value']];
        $subject = new NativeArrayGateway($fixture);
        $value = $subject['non_existent'];
        $this->assertNull($value);
    }

    public function testMutabilitySetFails()
    {
        $this->setExpectedException('\BadMethodCallException');
        $fixture = ['test' => ['sub' => 'value']];
        $subject = new NativeArrayGateway($fixture);
        $subject['non_existent'] = "fail";
    }

    public function testMutabilityUnsetFails()
    {
        $this->setExpectedException('\BadMethodCallException');
        $fixture = ['test' => ['sub' => 'value']];
        $subject = new NativeArrayGateway($fixture);
        unset($subject['test']);
    }
}


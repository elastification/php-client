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

    public function testIssetAndOffsetExists()
    {
        $fixture = ['test' => ['sub' => 'value']];
        $subject = new NativeArrayGateway($fixture);
        $this->assertTrue(isset($subject['test']));
        $this->assertTrue($subject->offsetExists('test'));
    }

    public function testIterator()
    {
        $fixture = ['test' => '1', 'test2' => '2'];
        $subject = new NativeArrayGateway($fixture);

        $this->assertEquals('test', $subject->key());
        $this->assertEquals('1', $subject->current());

        $subject->next();
        $this->assertEquals('test2', $subject->key());
        $this->assertEquals('2', $subject->current());

        $this->assertTrue($subject->valid());
        $subject->next();
        $this->assertFalse($subject->valid());

        $subject->rewind();
        $this->assertEquals('test', $subject->key());
        $this->assertEquals('1', $subject->current());

    }

    public function testCount()
    {
        $fixture = ['test' => '1', 'test2' => '2'];
        $subject = new NativeArrayGateway($fixture);
        $this->assertCount(2, $subject);
    }

    public function testGatewayValue()
    {
        $fixture = ['test' => '1', 'test2' => '2'];
        $subject = new NativeArrayGateway($fixture);
        $this->assertSame($fixture, $subject->getGatewayValue());
    }
}

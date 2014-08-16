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

    public function testMutabilitySetFails()
    {
        $this->setExpectedException('\BadMethodCallException');
        $fixture = new \stdClass();
        $subject = new NativeObjectGateway($fixture);
        $subject['test'] = "asdf";
    }

    public function testMutabilityUnsetFails()
    {
        $this->setExpectedException('\BadMethodCallException');
        $fixture = new \stdClass();
        $subject = new NativeObjectGateway($fixture);
        unset($subject['test']);
    }

    public function testIterator()
    {
        $fixture = new \stdClass();
        $fixture->test = 1;
        $fixture->test2 = 2;
        $subject = new NativeObjectGateway($fixture);

        $this->assertEquals('test', $subject->key());

        $subject->next();
        $this->assertEquals('test2', $subject->key());
        $this->assertEquals('2', $subject->current());

        $this->assertTrue($subject->valid());
        $subject->next();
        $this->assertFalse($subject->valid());
    }

    public function testCount()
    {
        $fixture = new \stdClass();
        $fixture->test = 1;
        $fixture->test2 = 2;
        $subject = new NativeObjectGateway($fixture);
        $this->assertCount(2, $subject);
    }

    public function testGatewayValue()
    {
        $fixture = new \stdClass();
        $fixture->test = 1;
        $fixture->test2 = 2;
        $subject = new NativeObjectGateway($fixture);
        $this->assertSame($fixture, $subject->getGatewayValue());
    }
}

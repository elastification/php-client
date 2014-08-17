<?php
namespace Elastification\Client\Tests\Unit\Serializer;

use Elastification\Client\Serializer\NativeJsonSerializer;

/**
 * @package Elastification\Client\Tests\Unit\Serializer
 * @author  Mario Mueller <mueller@freshcells.de>
 * @since   2014-08-13
 * @version 1.0.0
 */
class NativeJsonSerializerTest extends \PHPUnit_Framework_TestCase
{

    public function testNativeJsonSerializationWorksWithArray()
    {
        $subject = new NativeJsonSerializer();
        $fixture = '{"test":"value"}';
        $expected = ['test' => 'value'];
        $result = $subject->serialize($expected, ['assoc' => true]);
        $this->assertEquals($fixture, $result);
    }

    public function testNativeJsonSerializationWorksWithStdClass()
    {
        $subject = new NativeJsonSerializer();
        $fixture = '{"test":"value"}';
        $expected = new \stdClass();
        $expected->test = 'value';

        $result = $subject->serialize($expected);
        $this->assertEquals($fixture, $result);
    }

    public function testNativeJsonDeserializationWorksWithArray()
    {
        $subject = new NativeJsonSerializer();
        $expected = '{"test":"value"}';
        $fixture = ['test' => 'value'];
        $result = $subject->deserialize($expected, ['assoc' => true]);
        $this->assertEquals($fixture['test'], $result['test']);
    }


    public function testNativeJsonDeserializationThrowsExceptionOnInvalidJson()
    {
        $this->setExpectedException('Elastification\Client\Serializer\Exception\DeserializationFailureException');
        $subject = new NativeJsonSerializer();
        $expected = '{"test: ["value"}';
        $subject->deserialize($expected, ['assoc' => true]);
    }

    public function testNativeJsonDeserializationWorksWithStdClass()
    {
        $subject = new NativeJsonSerializer();
        $expected = '{"test":"value"}';
        $fixture = new \stdClass();
        $fixture->test = 'value';

        $result = $subject->deserialize($expected);
        $this->assertEquals($fixture->test, $result['test']);
    }

    public function testNativeJsonDeserializationWorksWithStdClassAssocFalse()
    {
        $subject = new NativeJsonSerializer();
        $expected = '{"test":"value"}';
        $fixture = new \stdClass();
        $fixture->test = 'value';

        $result = $subject->deserialize($expected, ['assoc' => false]);
        $this->assertEquals($fixture->test, $result['test']);
    }
}

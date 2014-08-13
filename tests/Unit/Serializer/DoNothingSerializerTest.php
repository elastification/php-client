<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Elastification\Client\Tests\Unit\Serializer;

use Elastification\Client\Serializer\DoNothingSerializer;
use Elastification\Client\Serializer\SerializerInterface;

class DoNothingSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  SerializerInterface */
    private $serializer;

    protected function setUp()
    {
        $this->serializer = new DoNothingSerializer();
    }

    protected function tearDown()
    {
        $this->serializer = null;
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\Serializer\SerializerInterface', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Serializer\DoNothingSerializer', $this->serializer);
    }

    public function testSerialize()
    {
        $data = new \stdClass();
        $data->name = 'test';
        $data->value = 1;

        $this->assertSame($data, $this->serializer->serialize($data));
    }

    public function testDeserialize()
    {
        $data = 'testDesrializeString';

        $this->assertSame($data, $this->serializer->deserialize($data));
    }
}
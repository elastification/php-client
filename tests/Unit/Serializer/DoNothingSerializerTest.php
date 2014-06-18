<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 19:02
 */
namespace Dawen\Component\Elastic\Tests\Unit\Serializer;

use Dawen\Component\Elastic\Serializer\DoNothingSerializer;
use Dawen\Component\Elastic\Serializer\SerializerInterface;

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
        $this->assertInstanceOf('Dawen\Component\Elastic\Serializer\SerializerInterface', $this->serializer);
        $this->assertInstanceOf('Dawen\Component\Elastic\Serializer\DoNothingSerializer', $this->serializer);
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
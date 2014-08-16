<?php
namespace Elastification\Client\Tests\Unit\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Elastification\Client\Serializer\JmsSerializer\SourceSubscribingHandler;
use Elastification\Client\Serializer\JmsSerializer\TestEntity;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Prophecy\PhpUnit\ProphecyTestCase;

/**
 * @package Elastification\Client\Tests\Unit\Serializer
 * @author Mario Mueller
 * @since 2014-08-15
 * @version 1.0.0
 */
class JmsSerializerTest extends ProphecyTestCase
{
    /**
     * @var Serializer
     */
    private $realJms;

    /**
     * @var Serializer
     */
    private $mockJms;

    /**
     * @var SourceSubscribingHandler
     */
    private $customHandler;

    /**
     * @author Mario Mueller
     */
    public function setUp()
    {
        // needed for prophecy
        parent::setUp();
        $this->customHandler = new SourceSubscribingHandler(
            'Elastification\Client\Tests\Fixtures\Unit\Serializer\JmsSerializer\TestEntity'
        );
        $this->realJms = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler($this->customHandler);
                }
            )
//                ->setCacheDir(sys_get_temp_dir().'/serializer')
            ->build();
    }


    public function testDeSerMessage()
    {
        $fixture = file_get_contents(FIXTURE_ROOT . '/Unit/Serializer/JmsSerializer/test_entity_1.json');
        $resultEntity = $this->realJms->deserialize(
            $fixture,
            'Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity',
            'json'
        );

        $this->assertInstanceOf('Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity', $resultEntity);

        /* @var $resultEntity \Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity */
        $this->assertEquals(1, $resultEntity->took);
        $this->assertEquals(false, $resultEntity->timed_out);

        $this->assertInstanceOf('Elastification\Client\Serializer\JmsSerializer\Shards', $resultEntity->_shards);
        $this->assertEquals(1, $resultEntity->_shards->total);
        $this->assertEquals(1, $resultEntity->_shards->successful);
        $this->assertEquals(0, $resultEntity->_shards->failed);

        $this->assertInstanceOf('Elastification\Client\Serializer\JmsSerializer\Hits', $resultEntity->hits);
        $this->assertEquals(1, $resultEntity->hits->total);
        $this->assertEquals(0, $resultEntity->hits->maxScore);
        $this->assertCount(1, $resultEntity->hits->hits);

        $hit = $resultEntity->hits->hits[0];
        $this->assertInstanceOf('Elastification\Client\Serializer\JmsSerializer\Hit', $hit);
        $this->assertEquals('4372-4412104-928-DL', $hit->_id);
        $this->assertEquals('elastification', $hit->_index);
        $this->assertEquals('test', $hit->_type);
        $this->assertEquals(1.993935, $hit->_score);

        $entity = $hit->_source;
        $this->assertInstanceOf(
            'Elastification\Client\Tests\Fixtures\Unit\Serializer\JmsSerializer\TestEntity',
            $entity
        );
        $this->assertEquals(123, $entity->a);
    }
}

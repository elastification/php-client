<?php
namespace Elastification\Client\Tests\Unit\Serializer;

use Elastification\Client\Serializer\JmsSerializer;
use Elastification\Client\Serializer\JmsSerializer\SourceSubscribingHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
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
            ->build();
    }


    public function testCustomSubscribingHandler()
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

    /**
     * Make the damn line coverage happy.
     * @author Mario Mueller
     */
    public function testDefaultValues()
    {
        $defaultDeSerClass = 'Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity';
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $subject = new JmsSerializer($jmsMock->reveal());

        $this->assertEquals($defaultDeSerClass, $subject->getDeserializerClass());

        $subject->setDeserializerClass('something');
        $this->assertEquals('something', $subject->getDeserializerClass());
        $subject->setDeserializerClass($defaultDeSerClass);

        $serCtx = $this->prophesize('JMS\Serializer\SerializationContext');
        $subject->setJmsSerializeContext($serCtx->reveal());
        $this->assertEquals($serCtx->reveal(), $subject->getJmsSerializeContext());

        $deSerCtx = $this->prophesize('JMS\Serializer\DeserializationContext');
        $subject->setJmsDeserializeContext($deSerCtx->reveal());
        $this->assertEquals($deSerCtx->reveal(), $subject->getJmsDeserializeContext());
    }

    public function testSerializer()
    {
        $fixture = new \stdClass();
        $fixture->a = 123;

        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $jmsMock->serialize($fixture, 'json', null)
            ->shouldBeCalled()
            ->willReturn('test_ok');

        $subject = new JmsSerializer($jmsMock->reveal());
        $ret = $subject->serialize($fixture);
        $this->assertEquals('test_ok', $ret);
    }

    public function testSerializerWithContextSetInInstance()
    {
        $fixture = new \stdClass();
        $fixture->a = 123;

        $jmsCtx = $this->prophesize('JMS\Serializer\SerializationContext');
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $jmsMock->serialize($fixture, 'json', $jmsCtx)
            ->shouldBeCalled()
            ->willReturn('test_ok');

        $subject = new JmsSerializer($jmsMock->reveal());
        $subject->setJmsSerializeContext($jmsCtx->reveal());

        $ret = $subject->serialize($fixture);
        $this->assertEquals('test_ok', $ret);
    }

    public function testSerializerWithContextSetInParams()
    {
        $fixture = new \stdClass();
        $fixture->a = 123;

        $jmsCtx = $this->prophesize('JMS\Serializer\SerializationContext');
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $jmsMock->serialize($fixture, 'json', $jmsCtx)
            ->shouldBeCalled()
            ->willReturn('test_ok');
        $subject = new JmsSerializer($jmsMock->reveal());
        $ret = $subject->serialize($fixture, ['ctx' => $jmsCtx->reveal()]);
        $this->assertEquals('test_ok', $ret);
    }

    public function testSerializerWithContextSetInInstanceAndParams()
    {
        $fixture = new \stdClass();
        $fixture->a = 123;

        $jmsCtx = $this->prophesize('JMS\Serializer\SerializationContext');
        $jmsCtx_MustWin = $this->prophesize('JMS\Serializer\SerializationContext');
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $jmsMock->serialize($fixture, 'json', $jmsCtx_MustWin)
            ->shouldBeCalled()
            ->willReturn('test_ok');
        $subject = new JmsSerializer($jmsMock->reveal());
        $subject->setJmsSerializeContext($jmsCtx->reveal());
        $ret = $subject->serialize($fixture, ['ctx' => $jmsCtx_MustWin->reveal()]);
        $this->assertEquals('test_ok', $ret);
    }


    public function testDeserializer()
    {
        $fixture = '{"a": 123}';
        $defaultDeSerClass = 'Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity';
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $jmsMock->deserialize($fixture, $defaultDeSerClass, 'json', null)
            ->shouldBeCalled()
            ->willReturn(new $defaultDeSerClass);

        $subject = new JmsSerializer($jmsMock->reveal());
        $ret = $subject->deserialize($fixture);
        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeObjectGateway', $ret);
    }

    public function testDeserializerWithContextSetInInstance()
    {
        $fixture = '{"a": 123}';
        $defaultDeSerClass = 'Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity';
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $deSerCtx = $this->prophesize('JMS\Serializer\DeserializationContext');

        $jmsMock->deserialize($fixture, $defaultDeSerClass, 'json', $deSerCtx)
            ->shouldBeCalled()
            ->willReturn(new $defaultDeSerClass);

        $subject = new JmsSerializer($jmsMock->reveal());
        $subject->setJmsDeserializeContext($deSerCtx->reveal());
        $ret = $subject->deserialize($fixture);
        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeObjectGateway', $ret);
    }

    public function testDeserializerWithContextSetInParams()
    {
        $fixture = '{"a": 123}';
        $defaultDeSerClass = 'Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity';
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $deSerCtx = $this->prophesize('JMS\Serializer\DeserializationContext');

        $jmsMock->deserialize($fixture, $defaultDeSerClass, 'json', $deSerCtx)
            ->shouldBeCalled()
            ->willReturn(new $defaultDeSerClass);

        $subject = new JmsSerializer($jmsMock->reveal());
        $ret = $subject->deserialize($fixture, ['ctx' => $deSerCtx->reveal()]);
        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeObjectGateway', $ret);
    }

    public function testDeserializerWithContextSetInInstanceAndParams()
    {
        $fixture = '{"a": 123}';
        $defaultDeSerClass = 'Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity';
        $jmsMock = $this->prophesize('JMS\Serializer\Serializer');
        $deSerCtx = $this->prophesize('JMS\Serializer\DeserializationContext');
        $deSerCtx_MustWin = $this->prophesize('JMS\Serializer\DeserializationContext');

        $jmsMock->deserialize($fixture, $defaultDeSerClass, 'json', $deSerCtx_MustWin)
            ->shouldBeCalled()
            ->willReturn(new $defaultDeSerClass);
        $subject = new JmsSerializer($jmsMock->reveal());
        $subject->setJmsDeserializeContext($deSerCtx->reveal());
        $ret = $subject->deserialize($fixture, ['ctx' => $deSerCtx_MustWin->reveal()]);
        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeObjectGateway', $ret);
    }
}

<?php
namespace Elastification\Client\Tests\Integration\Request\V090x;


use Elastification\Client\Request\V090x\CountRequest;
use Elastification\Client\Response\V090x\CountResponse;

class CountRequestTest extends AbstractElastic
{

    const TYPE = 'request-count';


    public function testCount()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $countRequest = new CountRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        /** @var CountResponse $response */
        $response = $this->getClient()->send($countRequest);

        $this->assertSame(3, $response->getCount());

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
    }

    public function testCountWithBody()
    {
        $this->createIndex();
        $data = array('name' => 'test', 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test', 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'mega', 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $countRequest = new CountRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $countRequest->setBody(array('term' => array('name' => 'test')));

        /** @var CountResponse $response */
        $response = $this->getClient()->send($countRequest);

        $this->assertSame(2, $response->getCount());

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
    }
}

<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Request\V090x\Index\IndexStatusRequest;
use Elastification\Client\Response\V090x\Index\IndexStatusResponse;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

class IndexStatusRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-status';

    public function testIndexStatusWithIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexStatusRequest(ES_INDEX, null, $this->getSerializer());

        /** @var IndexStatusResponse $response */
        $response = $this->getClient()->send($indexStatsRequest);

        $this->assertTrue($response->isOk());

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));

        $indices = $response->getIndices();
        $this->assertTrue(isset($indices[ES_INDEX]));
    }

    public function testIndexStatusWithoutIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexStatusRequest(null, null, $this->getSerializer());

        /** @var IndexStatusResponse $response */
        $response = $this->getClient()->send($indexStatsRequest);

        $this->assertTrue($response->isOk());

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));

        $indices = $response->getIndices();
        $this->assertTrue(isset($indices[ES_INDEX]));
    }
}

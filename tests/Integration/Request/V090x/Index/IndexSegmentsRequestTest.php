<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Request\V090x\Index\IndexSegmentsRequest;
use Elastification\Client\Response\V090x\Index\IndexStatusResponse;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

class IndexSegmentsRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-segments';

    public function testIndexSegmentsWithIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexSegmentsRequest(ES_INDEX, null, $this->getSerializer());

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

    public function testIndexSegmentsWithoutIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexSegmentsRequest(null, null, $this->getSerializer());

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

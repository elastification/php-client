<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\V1x\Index\IndexStatsRequest;
use Elastification\Client\Request\V1x\Index\IndexTypeExistsRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Response\V1x\Index\IndexStatsResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class IndexStatsRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-stats';

    public function testIndexStatsWithIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexStatsRequest(ES_INDEX, null, $this->getSerializer());

        /** @var IndexStatsResponse $response */
        $response = $this->getClient()->send($indexStatsRequest);

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));

        $all = $response->getAll();
        $this->assertTrue(isset($all['primaries']));
        $this->assertTrue(isset($all['total']));

        $indices = $response->getIndices();
        $this->assertTrue(isset($indices[ES_INDEX]));
    }

    public function testIndexStatsWithoutIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexStatsRequest(null, null, $this->getSerializer());

        /** @var IndexStatsResponse $response */
        $response = $this->getClient()->send($indexStatsRequest);

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));

        $all = $response->getAll();
        $this->assertTrue(isset($all['primaries']));
        $this->assertTrue(isset($all['total']));

        $indices = $response->getIndices();
        $this->assertTrue(isset($indices[ES_INDEX]));
    }
}

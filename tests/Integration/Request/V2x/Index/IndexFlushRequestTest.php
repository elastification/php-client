<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\IndexFlushRequest;
use Elastification\Client\Response\V2x\Index\RefreshIndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class IndexFlushRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-flush';

    public function testIndexFlush()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);

        $flushIndexRequest = new IndexFlushRequest(ES_INDEX, null, $this->getSerializer());

        /** @var RefreshIndexResponse $response */
        $response = $this->getClient()->send($flushIndexRequest);

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
    }

}

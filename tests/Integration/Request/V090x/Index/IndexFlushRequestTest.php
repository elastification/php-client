<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Request\V090x\Index\IndexFlushRequest;
use Elastification\Client\Response\V090x\Index\RefreshIndexResponse;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

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

        $this->assertTrue($response->isOk());
        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
    }

}

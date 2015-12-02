<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\IndexOptimizeRequest;
use Elastification\Client\Response\V2x\Index\RefreshIndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class IndexOptimizeRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-optimize';

    public function testIndexOptimize()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);

        $refreshIndexRequest = new IndexOptimizeRequest(ES_INDEX, null, $this->getSerializer());

        /** @var RefreshIndexResponse $response */
        $response = $this->getClient()->send($refreshIndexRequest);

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
    }

}

<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Request\V1x\Index\RefreshIndexRequest;
use Elastification\Client\Response\V1x\Index\RefreshIndexResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class RefreshIndexRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-refresh';

    public function testRefreshIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);

        $refreshIndexRequest = new RefreshIndexRequest(ES_INDEX, null, $this->getSerializer());

        /** @var RefreshIndexResponse $response */
        $response = $this->getClient()->send($refreshIndexRequest);

        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
    }

}

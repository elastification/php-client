<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\ShardsCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class ShardsCatRequestTest extends AbstractElastic
{
    const TYPE = 'request-cat-shards';

    public function testHealthCat()
    {
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $shardsCatRequest = new ShardsCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($shardsCatRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertTrue(is_array($data));

        if (!empty($data)) {
            $index = $data[0];
            $this->assertGreaterThanOrEqual(1, $index);

            $this->assertArrayHasKey('index', $index);
            $this->assertArrayHasKey('shard', $index);
            $this->assertArrayHasKey('prirep', $index);
            $this->assertArrayHasKey('state', $index);
            $this->assertArrayHasKey('docs', $index);
            $this->assertArrayHasKey('store', $index);
            $this->assertArrayHasKey('ip', $index);
            $this->assertArrayHasKey('node', $index);
        }

    }

}

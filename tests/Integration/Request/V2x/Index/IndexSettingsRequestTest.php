<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\IndexSettingsRequest;
use Elastification\Client\Response\V2x\Index\IndexStatsResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class IndexSettingsRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-settings';

    public function testIndexSettingsWithIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexSettingsRequest(ES_INDEX, null, $this->getSerializer());

        /** @var IndexStatsResponse $response */
        $response = $this->getClient()->send($indexStatsRequest);

        $data = $response->getData()->getGatewayValue();
        $this->assertArrayHasKey(ES_INDEX, $data);
    }

    public function testIndexSettingsWithoutIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexStatsRequest = new IndexSettingsRequest(null, null, $this->getSerializer());

        /** @var IndexStatsResponse $response */
        $response = $this->getClient()->send($indexStatsRequest);

        $data = $response->getData()->getGatewayValue();
        $this->assertArrayHasKey(ES_INDEX, $data);
    }
}

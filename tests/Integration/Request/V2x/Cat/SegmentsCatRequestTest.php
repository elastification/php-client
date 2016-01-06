<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\Cat\SegmentsCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

class SegmentsCatRequestTest extends AbstractElastic
{
    const TYPE = 'request-cat-shards';

    public function testSegmentsCat()
    {
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $segmentsCatRequest = new SegmentsCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($segmentsCatRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertTrue(is_array($data));

        if (!empty($data)) {
            $index = $data[0];
            $this->assertGreaterThanOrEqual(12, $index);

            $this->assertArrayHasKey('index', $index);
            $this->assertArrayHasKey('shard', $index);
            $this->assertArrayHasKey('segment', $index);
            $this->assertArrayHasKey('generation', $index);
            $this->assertArrayHasKey('docs.count', $index);
            $this->assertArrayHasKey('docs.deleted', $index);
            $this->assertArrayHasKey('size', $index);
            $this->assertArrayHasKey('size.memory', $index);
            $this->assertArrayHasKey('committed', $index);
            $this->assertArrayHasKey('searchable', $index);
            $this->assertArrayHasKey('version', $index);
            $this->assertArrayHasKey('compound', $index);
        }

    }

}

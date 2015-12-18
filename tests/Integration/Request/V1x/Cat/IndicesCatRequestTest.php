<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\IndicesCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class IndicesCatRequestTest extends AbstractElastic
{
    const TYPE = 'request-cat-indices';

    public function testHealthCat()
    {
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $countCatRequest = new IndicesCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];

        $this->assertGreaterThanOrEqual(9, $index);

        $this->assertArrayHasKey('health', $index);
        $this->assertArrayHasKey('status', $index);
        $this->assertArrayHasKey('index', $index);
        $this->assertArrayHasKey('pri', $index);
        $this->assertArrayHasKey('rep', $index);
        $this->assertArrayHasKey('docs.count', $index);
        $this->assertArrayHasKey('docs.deleted', $index);
        $this->assertArrayHasKey('store.size', $index);
        $this->assertArrayHasKey('pri.store.size', $index);

        $this->assertSame(ES_INDEX, $index['index']);
        $this->assertEquals(2, $index['docs.count']);

    }

}

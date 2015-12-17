<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\FielddataCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class FielddataCatRequestTest extends AbstractElastic
{
    const TYPE = 'request-cat-count';

    public function testFielddataCat()
    {

        $countCatRequest = new FielddataCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];
        $this->assertCount(8, $index);

        $this->assertArrayHasKey('id', $index);
        $this->assertArrayHasKey('host', $index);
        $this->assertArrayHasKey('ip', $index);
        $this->assertArrayHasKey('node', $index);
        $this->assertArrayHasKey('total', $index);
        $this->assertArrayHasKey('metaData.priority.number', $index);
        $this->assertArrayHasKey('updatedAt', $index);
        $this->assertArrayHasKey('metaData.tags.key', $index);
    }

}

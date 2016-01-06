<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\Cat\FielddataCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

class FielddataCatRequestTest extends AbstractElastic
{
    public function testFielddataCat()
    {
        $countCatRequest = new FielddataCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];
        $this->assertGreaterThanOrEqual(5, $index);

        $this->assertArrayHasKey('id', $index);
        $this->assertArrayHasKey('host', $index);
        $this->assertArrayHasKey('ip', $index);
        $this->assertArrayHasKey('node', $index);
        $this->assertArrayHasKey('total', $index);
//        $this->assertArrayHasKey('metaData.priority.number', $index);
//        $this->assertArrayHasKey('updatedAt', $index);
//        $this->assertArrayHasKey('metaData.tags.key', $index);
    }

}

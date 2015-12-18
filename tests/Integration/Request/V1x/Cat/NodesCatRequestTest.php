<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\NodesCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class NodesCatRequestTest extends AbstractElastic
{
    public function testHealthCat()
    {
        $countCatRequest = new NodesCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];

        $this->assertGreaterThanOrEqual(8, $index);

        $this->assertArrayHasKey('host', $index);
        $this->assertArrayHasKey('ip', $index);
        $this->assertArrayHasKey('heap.percent', $index);
        $this->assertArrayHasKey('ram.percent', $index);
        $this->assertArrayHasKey('load', $index);
        $this->assertArrayHasKey('node.role', $index);
        $this->assertArrayHasKey('master', $index);
        $this->assertArrayHasKey('name', $index);

    }

}

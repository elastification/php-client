<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\Cat\HealthCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

class HealthCatRequestTest extends AbstractElastic
{
    public function testHealthCat()
    {
        $countCatRequest = new HealthCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];
        $this->assertGreaterThanOrEqual(11, $index);

        $this->assertArrayHasKey('epoch', $index);
        $this->assertArrayHasKey('timestamp', $index);
        $this->assertArrayHasKey('cluster', $index);
        $this->assertArrayHasKey('status', $index);
        $this->assertArrayHasKey('node.total', $index);
        $this->assertArrayHasKey('node.data', $index);
        $this->assertArrayHasKey('shards', $index);
        $this->assertArrayHasKey('pri', $index);
        $this->assertArrayHasKey('relo', $index);
        $this->assertArrayHasKey('init', $index);
        $this->assertArrayHasKey('unassign', $index);

        if (count($index) == 12) {
            $this->assertArrayHasKey('pending_tasks', $index);
        }
    }

}

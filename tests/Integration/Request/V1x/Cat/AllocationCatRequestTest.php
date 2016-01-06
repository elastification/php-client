<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\AllocationCatRequest;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class AllocationCatRequestTest extends AbstractElastic
{
    public function testAllocaitionCat()
    {
        $allocationCatRequest = new AllocationCatRequest(null, null, $this->getSerializer());
        $response = $this->getClient()->send($allocationCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];
        $this->assertCount(8, $index);

        $this->assertArrayHasKey('shards', $index);
        $this->assertArrayHasKey('disk.used', $index);
        $this->assertArrayHasKey('disk.avail', $index);
        $this->assertArrayHasKey('disk.total', $index);
        $this->assertArrayHasKey('disk.percent', $index);
        $this->assertArrayHasKey('host', $index);
        $this->assertArrayHasKey('ip', $index);
        $this->assertArrayHasKey('node', $index);

        $this->assertEquals(0, $index['shards']);
    }

}

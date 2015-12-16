<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\Cat\AllocationCatRequest;
use Elastification\Client\Response\Shared\Cat\AbstractAllocationCatResponse;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

class AllocationCatRequestTest extends AbstractElastic
{
    const TYPE = 'request-cat-allocation';

    public function testAliasesCat()
    {
        $allocationCatRequest = new AllocationCatRequest(null, null, $this->getSerializer());
        $response = $this->getClient()->send($allocationCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data[0];
        $this->assertCount(8, $index);

        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_SHARDS, $index);
        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_DISK_USED, $index);
        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_DISK_AVAIL, $index);
        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_DISK_TOTAL, $index);
        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_DISK_PERCENT, $index);
        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_HOST, $index);
        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_IP, $index);
        $this->assertArrayHasKey(AbstractAllocationCatResponse::PROP_NODE, $index);

        $this->assertEquals(0, $index[AbstractAllocationCatResponse::PROP_SHARDS]);
    }

}

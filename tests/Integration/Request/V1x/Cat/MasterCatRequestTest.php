<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\MasterCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class MasterCatRequestTest extends AbstractElastic
{
    public function testHealthCat()
    {
        $countCatRequest = new MasterCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];

        $this->assertGreaterThanOrEqual(4, $index);

        $this->assertArrayHasKey('id', $index);
        $this->assertArrayHasKey('host', $index);
        $this->assertArrayHasKey('ip', $index);
        $this->assertArrayHasKey('node', $index);

    }

}

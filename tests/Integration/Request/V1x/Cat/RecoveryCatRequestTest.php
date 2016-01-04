<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\RecoveryCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class RecoveryCatRequestTest extends AbstractElastic
{
    public function testHealthCat()
    {
        $countCatRequest = new RecoveryCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertTrue(is_array($data));

        if (!empty($data)) {
            $index = $data[0];
            $this->assertCount(13, $index);


            $this->assertArrayHasKey('index', $index);
            $this->assertArrayHasKey('shard', $index);
            $this->assertArrayHasKey('time', $index);
            $this->assertArrayHasKey('type', $index);
            $this->assertArrayHasKey('stage', $index);
            $this->assertArrayHasKey('source_host', $index);
            $this->assertArrayHasKey('target_host', $index);
            $this->assertArrayHasKey('repository', $index);
            $this->assertArrayHasKey('snapshot', $index);
            $this->assertArrayHasKey('files', $index);
            $this->assertArrayHasKey('files_percent', $index);
            $this->assertArrayHasKey('bytes', $index);
            $this->assertArrayHasKey('bytes_percent', $index);
        }

    }

}

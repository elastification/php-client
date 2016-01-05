<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\Cat\ThreadPoolCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

class ThreadPoolCatRequestTest extends AbstractElastic
{
    public function testHealthCat()
    {
        $countCatRequest = new ThreadPoolCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertTrue(is_array($data));

        if (!empty($data)) {
            $index = $data[0];
            $this->assertCount(11, $index);


            $this->assertArrayHasKey('host', $index);
            $this->assertArrayHasKey('ip', $index);
            $this->assertArrayHasKey('bulk.active', $index);
            $this->assertArrayHasKey('bulk.queue', $index);
            $this->assertArrayHasKey('bulk.rejected', $index);
            $this->assertArrayHasKey('index.active', $index);
            $this->assertArrayHasKey('index.queue', $index);
            $this->assertArrayHasKey('index.rejected', $index);
            $this->assertArrayHasKey('search.active', $index);
            $this->assertArrayHasKey('search.queue', $index);
            $this->assertArrayHasKey('search.rejected', $index);
        }

    }

}

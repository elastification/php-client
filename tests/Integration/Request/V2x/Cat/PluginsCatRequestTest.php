<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\Cat\PluginsCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

class PluginsCatRequestTest extends AbstractElastic
{
    public function testHealthCat()
    {
        $countCatRequest = new PluginsCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertTrue(is_array($data));
    }

}

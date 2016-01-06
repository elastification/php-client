<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\Cat\NodeAttrsCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

class NodeAttrCatRequestTest extends AbstractElastic
{
    public function testNodeAttrsCat()
    {
        $nodeAttrsCatRequest = new NodeAttrsCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($nodeAttrsCatRequest);

        $data = $response->getData()->getGatewayValue();


        $this->assertTrue(is_array($data));

        if (!empty($data)) {
            $index = $data[0];
            $this->assertGreaterThanOrEqual(2, $index);
        }

    }

}

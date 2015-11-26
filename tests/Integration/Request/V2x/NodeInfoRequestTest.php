<?php
namespace Elastification\Client\Tests\Integration\Request\V2x;

use Elastification\Client\Request\V2x\NodeInfoRequest;
use Elastification\Client\Response\V2x\NodeInfoResponse;

class NodeInfoRequestTest extends AbstractElastic
{

    const TYPE = 'request-node-info';

    public function testNodeInfo()
    {
        $countRequest = new NodeInfoRequest($this->getSerializer());

        /** @var NodeInfoResponse $response */
        $response = $this->getClient()->send($countRequest);

        $this->assertNotEmpty($response->getName());
        $this->assertNotEmpty($response->getClusterName());
        $this->assertNotEmpty($response->getTagline());
        $this->assertArrayHasKey('number', $response->getVersion());
    }


}

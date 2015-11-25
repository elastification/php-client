<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Request\V090x\Index\GetAliasesRequest;
use Elastification\Client\Request\V090x\Index\UpdateAliasesRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

class UpdateAliasesRequestTest extends AbstractElastic
{

    public function testAliases()
    {
        $this->createIndex();
        $this->refreshIndex();

        $aliases = [
            'actions' => [
                [
                    'add' => [
                        'index' => ES_INDEX,
                        'alias' => 'alias-' . ES_INDEX
                    ]
                ]
            ]
        ];

        $aliasesRequest = new UpdateAliasesRequest(null, null, $this->getSerializer());
        $aliasesRequest->setBody($aliases);

        /** @var Response $response */
        $response = $this->getClient()->send($aliasesRequest);
        $this->assertTrue($response->acknowledged());

        $getAliasesRequest = new GetAliasesRequest(null, null, $this->getSerializer());

        /** @var Response $response */
        $response = $this->getClient()->send($getAliasesRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertArrayHasKey(ES_INDEX, $data);
        $this->assertTrue(isset($data[ES_INDEX]['aliases']['alias-' . ES_INDEX]));
    }

}

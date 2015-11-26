<?php
namespace Elastification\Client\Tests\Integration\Request\V2x;


use Elastification\Client\Request\V2x\Index\GetAliasesRequest;
use Elastification\Client\Request\V2x\AliasesRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\V2x\Index\IndexResponse;

class AliasesRequestTest extends AbstractElastic
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

        $aliasesRequest = new AliasesRequest(null, null, $this->getSerializer());
        $aliasesRequest->setBody($aliases);

        /** @var IndexResponse $response */
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

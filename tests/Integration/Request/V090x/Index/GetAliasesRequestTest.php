<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Request\V090x\AliasesRequest;
use Elastification\Client\Request\V090x\Index\GetAliasesRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\V090x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

class GetAliasesRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-get-aliases';

    public function testGetAliasesWithoutIndex()
    {
        $this->createIndex();

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
        $this->getClient()->send($aliasesRequest);

        $getAliasesRequest = new GetAliasesRequest(null, null, $this->getSerializer());

        /** @var Response $response */
        $response = $this->getClient()->send($getAliasesRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertArrayHasKey(ES_INDEX, $data);
        $this->assertTrue(isset($data[ES_INDEX]['aliases']['alias-' . ES_INDEX]));
    }

    public function testGetAliasesWitIndex()
    {
        $this->createIndex();

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
        $this->getClient()->send($aliasesRequest);

        $getAliasesRequest = new GetAliasesRequest(ES_INDEX, null, $this->getSerializer());

        /** @var Response $response */
        $response = $this->getClient()->send($getAliasesRequest);

        $data = $response->getData()->getGatewayValue();

        $this->assertArrayHasKey(ES_INDEX, $data);
        $this->assertTrue(isset($data[ES_INDEX]['aliases']['alias-' . ES_INDEX]));
    }
}

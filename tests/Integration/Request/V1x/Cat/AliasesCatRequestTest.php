<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\AliasesRequest;
use Elastification\Client\Request\V1x\Cat\AliasesCatRequest;
use Elastification\Client\Response\V1x\CountResponse;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class AliasesCatRequestTest extends AbstractElastic
{

    public function testAliasesCat()
    {
        $alias = 'alias-' . ES_INDEX;

        $this->createIndex();
        $this->refreshIndex();

        $aliases = [
            'actions' => [
                [
                    'add' => [
                        'index' => ES_INDEX,
                        'alias' => $alias
                    ]
                ]
            ]
        ];

        $aliasesRequest = new AliasesRequest(null, null, $this->getSerializer());
        $aliasesRequest->setBody($aliases);

        $this->getClient()->send($aliasesRequest);

        $aliasesCatRequest = new AliasesCatRequest(null, null, $this->getSerializer());

        /** @var CountResponse $response */
        $response = $this->getClient()->send($aliasesCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];
        $this->assertCount(5, $index);

        $this->assertArrayHasKey('alias', $index);
        $this->assertArrayHasKey('index', $index);
        $this->assertArrayHasKey('filter', $index);
        $this->assertArrayHasKey('routing.index', $index);
        $this->assertArrayHasKey('routing.search', $index);

        $this->assertSame($alias, $index['alias']);
        $this->assertSame(ES_INDEX, $index['index']);
        $this->assertSame('-', $index['filter']);
        $this->assertSame('-', $index['routing.index']);
        $this->assertSame('-', $index['routing.search']);
    }

}

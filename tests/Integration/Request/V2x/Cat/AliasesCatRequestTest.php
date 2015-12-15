<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Cat;


use Elastification\Client\Request\V2x\AliasesRequest;
use Elastification\Client\Request\V2x\Cat\AliasesCatRequest;
use Elastification\Client\Response\Shared\Cat\AbstractAliasesCatResponse;
use Elastification\Client\Response\V2x\CountResponse;
use Elastification\Client\Tests\Integration\Repository\V2x\AbstractElastic;

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

        $index = $data[0];
        $this->assertCount(5, $index);

        $this->assertArrayHasKey(AbstractAliasesCatResponse::PROP_ALIAS, $index);
        $this->assertArrayHasKey(AbstractAliasesCatResponse::PROP_INDEX, $index);
        $this->assertArrayHasKey(AbstractAliasesCatResponse::PROP_FILTER, $index);
        $this->assertArrayHasKey(AbstractAliasesCatResponse::PROP_ROUTING_INDEX, $index);
        $this->assertArrayHasKey(AbstractAliasesCatResponse::PROP_ROUTING_SEARCH, $index);

        $this->assertSame($alias, $index[AbstractAliasesCatResponse::PROP_ALIAS]);
        $this->assertSame(ES_INDEX, $index[AbstractAliasesCatResponse::PROP_INDEX]);
        $this->assertSame('-', $index[AbstractAliasesCatResponse::PROP_FILTER]);
        $this->assertSame('-', $index[AbstractAliasesCatResponse::PROP_ROUTING_INDEX]);
        $this->assertSame('-', $index[AbstractAliasesCatResponse::PROP_ROUTING_SEARCH]);
    }

}

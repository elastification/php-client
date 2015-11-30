<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\CreateWarmerRequest;
use Elastification\Client\Request\V2x\Index\DeleteWarmerRequest;
use Elastification\Client\Request\V2x\Index\GetWarmerRequest;
use Elastification\Client\Response\V2x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class CreateDeleteGetWarmerRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-create-get-delete-warmer';

    public function testCreateDeleteWarmer()
    {
        $this->createIndex();
        $this->refreshIndex();

        $warmerName = 'test_warmer';
        $warmer = [
            'query' => [
                'match_all' => []
            ]
        ];

        $createWarmerRequest = new CreateWarmerRequest(ES_INDEX, null, $this->getSerializer());
        $createWarmerRequest->setWarmerName($warmerName);
        $createWarmerRequest->setBody($warmer);

        /** @var IndexResponse $createResponse */
        $createResponse = $this->getClient()->send($createWarmerRequest);

        $this->assertTrue($createResponse->acknowledged());
        $this->refreshIndex();

        $getWarmerRequest = new GetWarmerRequest(ES_INDEX, null, $this->getSerializer());
        $getWarmerRequest->setWarmerName($warmerName);

        $getResponse = $this->getClient()->send($getWarmerRequest);
        $data = $getResponse->getData()->getGatewayValue();
        $this->assertArrayHasKey($warmerName, $data[ES_INDEX]['warmers']);

        $deleteRequest = new DeleteWarmerRequest(ES_INDEX, null, $this->getSerializer());
        $deleteRequest->setWarmerName($warmerName);

        /** @var IndexResponse $deleteResponse */
        $deleteResponse = $this->getClient()->send($deleteRequest);

        $this->assertTrue($deleteResponse->acknowledged());

        $getWarmerRequest = new GetWarmerRequest(ES_INDEX, null, $this->getSerializer());
        $getWarmerRequest->setWarmerName($warmerName);

        $getResponse = $this->getClient()->send($getWarmerRequest);

        $this->assertEmpty($getResponse->getData()->getGatewayValue());
    }

}

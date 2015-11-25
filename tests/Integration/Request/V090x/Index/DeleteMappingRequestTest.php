<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\V090x\Index\CreateMappingRequest;
use Elastification\Client\Request\V090x\Index\DeleteMappingRequest;
use Elastification\Client\Request\V090x\Index\GetMappingRequest;
use Elastification\Client\Response\V090x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

class DeleteMappingRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-delete-mapping';

    public function testDeleteMappingWithIndexAndType()
    {
        $this->createIndex();
        $mapping = [
            self::TYPE => [
                'properties' => [
                    'message' => ['type' => 'string']
                ]
            ]
        ];

        $createMappingRequest = new CreateMappingRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $createMappingRequest->setBody($mapping);

        $this->getClient()->send($createMappingRequest);

        $deleteMappingRequest = new DeleteMappingRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        /** @var IndexResponse $response */
        $response = $this->getClient()->send($deleteMappingRequest);

        $this->assertTrue($response->isOk());
        $this->assertTrue($response->acknowledged());


        try {
            //check if exists
            $getMappingRequest = new GetMappingRequest(ES_INDEX, self::TYPE, $this->getSerializer());

            $response = $this->getClient()->send($getMappingRequest);
        } catch (ClientException $exception) {
            $this->assertSame(404, $exception->getCode());
            return;
        }

        $this->fail();
    }
}

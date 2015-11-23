<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Request\V1x\Index\CreateMappingRequest;
use Elastification\Client\Request\V1x\Index\DeleteMappingRequest;
use Elastification\Client\Request\V1x\Index\GetMappingRequest;
use Elastification\Client\Response\V1x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

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

        $this->assertTrue($response->acknowledged());

        //check if exists
        $getMappingRequest = new GetMappingRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $response = $this->getClient()->send($getMappingRequest);
        $this->assertSame('{}', $response->getRawData());
    }
}

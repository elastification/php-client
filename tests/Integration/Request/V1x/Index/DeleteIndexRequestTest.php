<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Request\V1x\Index\DeleteIndexRequest;
use Elastification\Client\Response\V1x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class DeleteIndexRequestTest extends AbstractElastic
{

    public function testDeleteIndex()
    {
        $this->createIndex();
        $this->assertTrue($this->hasIndex());

        $createIndexRequest = new DeleteIndexRequest(ES_INDEX, null, $this->getSerializer());

        /** @var IndexResponse $response */
        $response = $this->getClient()->send($createIndexRequest);

        $this->assertTrue($response->acknowledged());
        $this->assertFalse($this->hasIndex());
    }

}

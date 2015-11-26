<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\DeleteIndexRequest;
use Elastification\Client\Response\V2x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

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

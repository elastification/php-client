<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Request\V090x\Index\DeleteIndexRequest;
use Elastification\Client\Response\V090x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

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
        $this->assertTrue($response->isOk());
        $this->assertFalse($this->hasIndex());
    }

}

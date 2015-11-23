<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Request\V1x\Index\CloseIndexRequest;
use Elastification\Client\Response\V1x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class CloseIndexRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-delete-mapping';

    public function testCloseIndex()
    {
        $this->createIndex();

        $closeRequest = new CloseIndexRequest(ES_INDEX, null, $this->getSerializer());
        /** @var IndexResponse $closeResponse */
        $closeResponse = $this->getClient()->send($closeRequest);

        $this->assertTrue($closeResponse->acknowledged());
    }
}

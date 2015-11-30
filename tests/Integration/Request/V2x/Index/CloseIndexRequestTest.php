<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\CloseIndexRequest;
use Elastification\Client\Response\V2x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class CloseIndexRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-close';

    public function testCloseIndex()
    {
        $this->createIndex();

        $closeRequest = new CloseIndexRequest(ES_INDEX, null, $this->getSerializer());
        /** @var IndexResponse $closeResponse */
        $closeResponse = $this->getClient()->send($closeRequest);

        $this->assertTrue($closeResponse->acknowledged());
    }
}

<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\CloseIndexRequest;
use Elastification\Client\Request\V2x\Index\OpenIndexRequest;
use Elastification\Client\Response\V2x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class OpenIndexRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-open';

    public function testOpenIndexWithClosedBefore()
    {
        $this->createIndex();

        $closeRequest = new CloseIndexRequest(ES_INDEX, null, $this->getSerializer());
        /** @var IndexResponse $closeResponse */
        $closeResponse = $this->getClient()->send($closeRequest);

        $this->assertTrue($closeResponse->acknowledged());

        $openRequest = new OpenIndexRequest(ES_INDEX, null, $this->getSerializer());
        /** @var IndexResponse $closeResponse */
        $openResponse = $this->getClient()->send($openRequest);

        $this->assertTrue($openResponse->acknowledged());
    }

    public function testOpenIndexWithNotClosedBefore()
    {
        $this->createIndex();

        $openRequest = new OpenIndexRequest(ES_INDEX, null, $this->getSerializer());
        /** @var IndexResponse $closeResponse */
        $openResponse = $this->getClient()->send($openRequest);

        $this->assertTrue($openResponse->acknowledged());
    }
}

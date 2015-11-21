<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Request\V1x\Index\IndexExistsRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class IndexExistsRequestTest extends AbstractElastic
{
    public function testIndexExists()
    {
        $this->createIndex();
        $this->refreshIndex();

        $indexExistsRequest = new IndexExistsRequest(ES_INDEX, null, $this->getSerializer());

        /** @var ResponseInterface $response */
        $response = $this->getClient()->send($indexExistsRequest);

        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
    }

}

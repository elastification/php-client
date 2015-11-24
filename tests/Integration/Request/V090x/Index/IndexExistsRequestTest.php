<?php
namespace Elastification\Client\Tests\Integration\Request\V090x\Index;


use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\V090x\Index\IndexExistsRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Tests\Integration\Request\V090x\AbstractElastic;

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

    public function testIndexExistsNotExisting()
    {
        $this->createIndex();
        $this->refreshIndex();

        $indexExistsRequest = new IndexExistsRequest('not-existing-index', null, $this->getSerializer());

        try {
            $this->getClient()->send($indexExistsRequest);
        } catch(ClientException $exception) {

            $this->assertSame(404, $exception->getCode());
            $this->assertContains('Client error:', $exception->getMessage());
            return;
        }

        $this->fail();
    }

}

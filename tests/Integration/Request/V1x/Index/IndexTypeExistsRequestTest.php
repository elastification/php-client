<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\V1x\Index\IndexTypeExistsRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class IndexTypeExistsRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-type-exists';


    public function testIndexTypeExists()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();


        $indexExistsRequest = new IndexTypeExistsRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        /** @var ResponseInterface $response */
        $response = $this->getClient()->send($indexExistsRequest);

        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
    }

    public function testIndexTypeExistsNotExisting()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();


        $indexExistsRequest = new IndexTypeExistsRequest(ES_INDEX, 'not-existing-type', $this->getSerializer());

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

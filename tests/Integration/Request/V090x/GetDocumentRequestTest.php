<?php
namespace Elastification\Client\Tests\Integration\Request\V090x;


use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\V090x\GetDocumentRequest;
use Elastification\Client\Response\V090x\DocumentResponse;

class GetDocumentRequestTest extends AbstractElastic
{

    const TYPE = 'request-get-document';

    public function testGetDocument()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $id = $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $getDocumentRequest = new GetDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $getDocumentRequest->setId($id);

        /** @var DocumentResponse $getDocumentResponse */
        $getDocumentResponse = $this->getClient()->send($getDocumentRequest);

        $this->assertSame($id, $getDocumentResponse->getId());
        $this->assertSame(1, $getDocumentResponse->getVersion());
        $this->assertSame(ES_INDEX, $getDocumentResponse->getIndex());
        $this->assertSame(self::TYPE, $getDocumentResponse->getType());
        $this->assertSame($data['name'], $getDocumentResponse->getSource()['name']);
        $this->assertSame($data['value'], $getDocumentResponse->getSource()['value']);
        $this->assertTrue($getDocumentResponse->exists());
    }

    public function testGetDocumentMissingDoc()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();


        $getDocumentRequest = new GetDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $getDocumentRequest->setId('notExisting');

        try {
            $this->getClient()->send($getDocumentRequest);
        } catch (ClientException $exception) {
            $this->assertSame(404, $exception->getCode());
            $this->assertContains('Client error:', $exception->getMessage());
            return;
        }

        $this->fail();
    }
}

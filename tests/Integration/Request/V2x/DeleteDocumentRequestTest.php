<?php
namespace Elastification\Client\Tests\Integration\Request\V2x;


use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\V2x\DeleteDocumentRequest;
use Elastification\Client\Request\V2x\GetDocumentRequest;
use Elastification\Client\Response\V2x\DeleteDocumentResponse;

class DeleteDocumentRequestTest extends AbstractElastic
{

    const TYPE = 'request-delete-document';

    public function testDeleteDocument()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $id = $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $deleteDocumentRequest = new DeleteDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $deleteDocumentRequest->setId($id);

        /** @var DeleteDocumentResponse $response */
        $response = $this->getClient()->send($deleteDocumentRequest);

        $this->assertTrue($response->found());
        $this->assertSame($id, $response->getId());
        $this->assertSame(ES_INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertGreaterThan(1, $response->getVersion());

        $getDocumentRequest = new GetDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $getDocumentRequest->setId($id);

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

<?php
namespace Elastification\Client\Tests\Integration\Request\V1x;


use Elastification\Client\Request\V1x\UpdateDocumentRequest;
use Elastification\Client\Response\V1x\CreateUpdateDocumentResponse;

class UpdateDocumentRequestTest extends AbstractElastic
{

    const TYPE = 'request-update-document';

    public function testUpdateDocument()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $id = $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $updateDocumentRequest = new UpdateDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $updateDocumentRequest->setId($id);
        $data['name'] = 'testName';
        $updateDocumentRequest->setBody($data);

        /** @var CreateUpdateDocumentResponse $updateDocumentResponse */
        $updateDocumentResponse = $this->getClient()->send($updateDocumentRequest);

        $this->assertSame(ES_INDEX, $updateDocumentResponse->getIndex());
        $this->assertSame(self::TYPE, $updateDocumentResponse->getType());
        $this->assertSame(2, $updateDocumentResponse->getVersion());
        $this->assertSame($id, $updateDocumentResponse->getId());
        $this->assertFalse($updateDocumentResponse->created());
    }

}

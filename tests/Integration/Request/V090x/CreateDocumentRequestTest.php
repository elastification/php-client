<?php
namespace Elastification\Client\Tests\Integration\Request\V090x;


use Elastification\Client\Request\V090x\CreateDocumentRequest;
use Elastification\Client\Response\V090x\CreateUpdateDocumentResponse;

class CreateDocumentRequestTest extends AbstractElastic
{

    const TYPE = 'request-create-document';

    public function testCreateDocument()
    {
        $this->createIndex();

        $createDocumentRequest = new CreateDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $createDocumentRequest->setBody($data);
        /** @var CreateUpdateDocumentResponse $response */
        $response = $this->getClient()->send($createDocumentRequest);

        $this->assertSame(ES_INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue(strlen($response->getId()) > 5);
        $this->assertTrue($response->isOk());
    }

}

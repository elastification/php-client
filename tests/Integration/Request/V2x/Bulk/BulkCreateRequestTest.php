<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Bulk;

use Elastification\Client\Request\Shared\Bulk\AbstractBulkCreateRequest;
use Elastification\Client\Request\V2x\Bulk\BulkCreateRequest;
use Elastification\Client\Response\V2x\BulkResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class BulkCreateRequestTest extends AbstractElastic
{

    const TYPE = 'bulk-create';

    public function testBulkCreate()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $allDocs = [$doc1, $doc2, $doc3];

        $bulkUpdateRequest = new BulkCreateRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkUpdateRequest->addDocument($doc1);
        $bulkUpdateRequest->addDocument($doc2, 'doc2');
        $bulkUpdateRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkUpdateRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkCreateRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkCreateRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkCreateRequest::BULK_ACTION]['_type']);
            $this->assertSame(1, $item[AbstractBulkCreateRequest::BULK_ACTION]['_version']);
            $this->assertSame(201, $item[AbstractBulkCreateRequest::BULK_ACTION]['status']);

            $doc = $this->getDocument(ES_INDEX, self::TYPE, $item[AbstractBulkCreateRequest::BULK_ACTION]['_id']);
            $this->assertEquals($allDocs[$key], $doc);
        }
    }

    public function testBulkCreateDocsAlreadyExists()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $allDocs = [$doc1, $doc2, $doc3];

        $bulkUpdateRequest = new BulkCreateRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkUpdateRequest->addDocument($doc1, 'doc1');
        $bulkUpdateRequest->addDocument($doc2, 'doc2');
        $bulkUpdateRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkUpdateRequest);
        $response = $this->getClient()->send($bulkUpdateRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertTrue($response->errors());


        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkCreateRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkCreateRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkCreateRequest::BULK_ACTION]['_type']);
            $this->assertSame(409, $item[AbstractBulkCreateRequest::BULK_ACTION]['status']);
            $this->assertContains('document already exists', $item[AbstractBulkCreateRequest::BULK_ACTION]['error']['reason']);

            $doc = $this->getDocument(ES_INDEX, self::TYPE, $item[AbstractBulkCreateRequest::BULK_ACTION]['_id']);
            $this->assertEquals($allDocs[$key], $doc);
        }
    }

}

<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Bulk;

use Elastification\Client\Request\Shared\Bulk\AbstractBulkCreateRequest;
use Elastification\Client\Request\Shared\Bulk\AbstractBulkIndexRequest;
use Elastification\Client\Request\V1x\Bulk\BulkIndexRequest;
use Elastification\Client\Response\V1x\BulkResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class BulkIndexRequestTest extends AbstractElastic
{

    const TYPE = 'bulk-index';

    public function testBulkCreate()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $allDocs = [$doc1, $doc2, $doc3];

        $bulkIndexRequest = new BulkIndexRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkIndexRequest->addDocument($doc1, 'doc1');
        $bulkIndexRequest->addDocument($doc2, 'doc2');
        $bulkIndexRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkIndexRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkIndexRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkIndexRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkIndexRequest::BULK_ACTION]['_type']);
            $this->assertSame(1, $item[AbstractBulkIndexRequest::BULK_ACTION]['_version']);
            $this->assertSame(201, $item[AbstractBulkIndexRequest::BULK_ACTION]['status']);

            $doc = $this->getDocument(ES_INDEX, self::TYPE, $item[AbstractBulkIndexRequest::BULK_ACTION]['_id']);
            $this->assertEquals($allDocs[$key], $doc);
        }
    }

    public function testBulkCreateFirstDocWithoutId()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $allDocs = [$doc1, $doc2, $doc3];

        $bulkIndexRequest = new BulkIndexRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkIndexRequest->addDocument($doc1);
        $bulkIndexRequest->addDocument($doc2, 'doc2');
        $bulkIndexRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkIndexRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            if(0 === $key) {
                $itemAction = $item[AbstractBulkCreateRequest::BULK_ACTION];
                $this->assertArrayHasKey(AbstractBulkCreateRequest::BULK_ACTION, $item);
            } else {
                $itemAction = $item[AbstractBulkIndexRequest::BULK_ACTION];
                $this->assertArrayHasKey(AbstractBulkIndexRequest::BULK_ACTION, $item);
            }

            $this->assertSame(ES_INDEX, $itemAction['_index']);
            $this->assertSame(self::TYPE, $itemAction['_type']);
            $this->assertSame(1, $itemAction['_version']);
            $this->assertSame(201, $itemAction['status']);

            $doc = $this->getDocument(ES_INDEX, self::TYPE, $itemAction['_id']);
            $this->assertEquals($allDocs[$key], $doc);
        }
    }

    public function testBulkCreateTwice()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $allDocs = [$doc1, $doc2, $doc3];

        $bulkIndexRequest = new BulkIndexRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkIndexRequest->addDocument($doc1, 'doc1');
        $bulkIndexRequest->addDocument($doc2, 'doc2');
        $bulkIndexRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkIndexRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkIndexRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkIndexRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkIndexRequest::BULK_ACTION]['_type']);
            $this->assertSame(1, $item[AbstractBulkIndexRequest::BULK_ACTION]['_version']);
            $this->assertSame(201, $item[AbstractBulkIndexRequest::BULK_ACTION]['status']);

            $doc = $this->getDocument(ES_INDEX, self::TYPE, $item[AbstractBulkIndexRequest::BULK_ACTION]['_id']);
            $this->assertEquals($allDocs[$key], $doc);
        }
    }


}

<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Bulk;

use Elastification\Client\Request\Shared\Bulk\AbstractBulkUpdateRequest;
use Elastification\Client\Request\V1x\Bulk\BulkCreateRequest;
use Elastification\Client\Request\V1x\Bulk\BulkUpdateRequest;
use Elastification\Client\Response\V1x\BulkResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class BulkUpdateRequestTest extends AbstractElastic
{

    const TYPE = 'bulk-update';

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testBulkUpdateDocsNotExisting()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $bulkCreateRequest = new BulkCreateRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $bulkCreateRequest->addDocument($doc1, 'doc1');
        $bulkCreateRequest->addDocument($doc2, 'doc2');
        $bulkCreateRequest->addDocument($doc3, 'doc3');

        $bulkUpdateRequest = new BulkUpdateRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $allDocs = [$doc1, $doc2, $doc3];

        $bulkUpdateRequest->addDocument($doc1, 'doc1', 2);
        $bulkUpdateRequest->addDocument($doc2, 'doc2', 1);
        $bulkUpdateRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkCreateRequest);
        $this->assertGreaterThan(0, $response->took());
        $this->assertFalse($response->errors());

        $response = $this->getClient()->send($bulkUpdateRequest);

        $this->assertGreaterThan(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkUpdateRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_type']);
            $this->assertSame(2, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_version']);
            $this->assertSame(200, $item[AbstractBulkUpdateRequest::BULK_ACTION]['status']);

            $doc = $this->getDocument(ES_INDEX, self::TYPE, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_id']);
            $this->assertEquals($allDocs[$key], $doc);
        }
    }

    public function testBulkUpdateOneExistingAndTwoMissingDocs()
    {
        $this->createIndex();

        //todo only partial errors
        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $allDocs = [$doc1, $doc2, $doc3];

        $bulkCreateRequest = new BulkCreateRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $bulkCreateRequest->addDocument($doc1, 'doc1');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkCreateRequest);
        $this->assertGreaterThan(0, $response->took());
        $this->assertFalse($response->errors());

        $bulkUpdateRequest = new BulkUpdateRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkUpdateRequest->addDocument($doc1, 'doc1');
        $bulkUpdateRequest->addDocument($doc2, 'doc2');
        $bulkUpdateRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkUpdateRequest);

        $this->assertGreaterThan(0, $response->took());
        $this->assertTrue($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            if (0 === $key) {
                $this->assertSame(200, $item[AbstractBulkUpdateRequest::BULK_ACTION]['status']);
                $doc = $this->getDocument(ES_INDEX, self::TYPE, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_id']);
                $this->assertEquals($allDocs[$key], $doc);
            } else {
                $this->assertSame(404, $item[AbstractBulkUpdateRequest::BULK_ACTION]['status']);
                $this->assertContains('[' . $item[AbstractBulkUpdateRequest::BULK_ACTION]['_id'] . ']: document missing',
                $item[AbstractBulkUpdateRequest::BULK_ACTION]['error']);
            }
            $this->assertArrayHasKey(AbstractBulkUpdateRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_type']);

        }
    }

    public function testBulkUpdateThreeMissingDocs()
    {
        $this->createIndex();

        //todo only partial errors
        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $bulkUpdateRequest = new BulkUpdateRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkUpdateRequest->addDocument($doc1, 'doc1');
        $bulkUpdateRequest->addDocument($doc2, 'doc2');
        $bulkUpdateRequest->addDocument($doc3, 'doc3');

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkUpdateRequest);

        $this->assertGreaterThan(0, $response->took());
        $this->assertTrue($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkUpdateRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkUpdateRequest::BULK_ACTION]['_type']);
            $this->assertSame(404, $item[AbstractBulkUpdateRequest::BULK_ACTION]['status']);
            $this->assertContains('[' . $item[AbstractBulkUpdateRequest::BULK_ACTION]['_id'] . ']: document missing',
                $item[AbstractBulkUpdateRequest::BULK_ACTION]['error']);
        }
    }

}

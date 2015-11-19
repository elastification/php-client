<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Bulk;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\Shared\Bulk\AbstractBulkDeleteRequest;
use Elastification\Client\Request\V1x\Bulk\BulkCreateRequest;
use Elastification\Client\Request\V1x\Bulk\BulkDeleteRequest;
use Elastification\Client\Request\V1x\GetDocumentRequest;
use Elastification\Client\Response\V1x\BulkResponse;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class BulkDeleteRequestTest extends AbstractElastic
{

    const TYPE = 'bulk-delete';

    public function testBulkDeleteAllExisting()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc2 = array('name' => 'test2' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $doc3 = array('name' => 'test3' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $bulkCreateRequest = new BulkCreateRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $bulkCreateRequest->addDocument($doc1, 'doc1');
        $bulkCreateRequest->addDocument($doc2, 'doc2');
        $bulkCreateRequest->addDocument($doc3, 'doc3');

        $bulkDeleteRequest = new BulkDeleteRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkDeleteRequest->add(array('doc1', 'doc2', 'doc3'));

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkCreateRequest);
        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $response = $this->getClient()->send($bulkDeleteRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkDeleteRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_type']);
            $this->assertSame(2, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_version']);
            $this->assertSame(200, $item[AbstractBulkDeleteRequest::BULK_ACTION]['status']);

            $docRequest = new GetDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
            $docRequest->setId($item[AbstractBulkDeleteRequest::BULK_ACTION]['_id']);

            try {
                $this->getClient()->send($docRequest);
                $this->fail('document isn\'t deleted');
            } catch (ClientException $exception) {
                $this->assertSame(404, $exception->getCode());
            }
        }
    }

    public function testBulkDeleteNotExistingDocs()
    {
        $this->createIndex();

        $bulkDeleteRequest = new BulkDeleteRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkDeleteRequest->add(array('doc1', 'doc2', 'doc3'));
        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkDeleteRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkDeleteRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_type']);
            $this->assertSame(1, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_version']);
            $this->assertSame(404, $item[AbstractBulkDeleteRequest::BULK_ACTION]['status']);

            $docRequest = new GetDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
            $docRequest->setId($item[AbstractBulkDeleteRequest::BULK_ACTION]['_id']);
        }
    }

    public function testBulkDeleteFirstExisting()
    {
        $this->createIndex();

        $doc1 = array('name' => 'test1' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $bulkCreateRequest = new BulkCreateRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $bulkCreateRequest->addDocument($doc1, 'doc1');

        $bulkDeleteRequest = new BulkDeleteRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $bulkDeleteRequest->add(array('doc1', 'doc2', 'doc3'));

        /** @var BulkResponse $response */
        $response = $this->getClient()->send($bulkCreateRequest);
        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $response = $this->getClient()->send($bulkDeleteRequest);

        $this->assertGreaterThanOrEqual(0, $response->took());
        $this->assertFalse($response->errors());

        $items = $response->getItems();
        $this->assertTrue(is_array($items));
        $this->assertCount(3, $items);

        foreach($items as $key => $item) {
            $this->assertArrayHasKey(AbstractBulkDeleteRequest::BULK_ACTION, $item);
            $this->assertSame(ES_INDEX, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_index']);
            $this->assertSame(self::TYPE, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_type']);

            if(0 === $key) {
                $this->assertSame(2, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_version']);
                $this->assertSame(200, $item[AbstractBulkDeleteRequest::BULK_ACTION]['status']);

                $docRequest = new GetDocumentRequest(ES_INDEX, self::TYPE, $this->getSerializer());
                $docRequest->setId($item[AbstractBulkDeleteRequest::BULK_ACTION]['_id']);

                try {
                    $this->getClient()->send($docRequest);
                    $this->fail('document isn\'t deleted');
                } catch (ClientException $exception) {
                    $this->assertSame(404, $exception->getCode());
                }
            } else {
                $this->assertSame(1, $item[AbstractBulkDeleteRequest::BULK_ACTION]['_version']);
                $this->assertSame(404, $item[AbstractBulkDeleteRequest::BULK_ACTION]['status']);
            }
        }
    }

}

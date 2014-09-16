<?php
namespace Elastification\Client\Tests\Integration;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\V1x\CreateDocumentRequest;
use Elastification\Client\Request\V1x\Index\CreateIndexRequest;
use Elastification\Client\Request\V1x\Index\DeleteIndexRequest;
use Elastification\Client\Request\V1x\Index\IndexExistsRequest;
use Elastification\Client\Request\V1x\Index\RefreshIndexRequest;
use Elastification\Client\Request\V1x\NodeInfoRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Response\V1x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V1x\Index\IndexResponse;
use Elastification\Client\Response\V1x\Index\RefreshIndexResponse;
use Elastification\Client\Response\V1x\NodeInfoResponse;
use Elastification\Client\Serializer\NativeJsonSerializer;
use Elastification\Client\Serializer\SerializerInterface;
use Elastification\Client\Transport\HttpGuzzle\GuzzleTransport;
use Elastification\Client\Transport\TransportInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Elastification\Client\Client;
use Elastification\Client\ClientInterface;

/**
 * @group es_090
 */
class SandboxV1xTest extends \PHPUnit_Framework_TestCase
{

    const INDEX = 'dawen-elastic';
    const TYPE = 'sandbox';

    private $url = 'http://192.168.33.132:9200/';

    /**
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var TransportInterface
     */
    private $transportClient;


    protected function setUp()
    {
        parent::setUp();

        $this->guzzleClient = new GuzzleClient(array('base_url' => $this->url));
        $this->transportClient = new GuzzleTransport($this->guzzleClient);
        $this->requestManager = new RequestManager();
        $this->client = new Client($this->transportClient, $this->requestManager);
        $this->serializer = new NativeJsonSerializer();
    }

    protected function tearDown()
    {
        parent::tearDown();

        if($this->hasIndex()) {
            $this->deleteIndex();
        }

        $this->guzzleClient = null;
        $this->requestManager = null;
        $this->client = null;
        $this->serializer = null;
        $this->transportClient = null;
    }

    public function testNodeInfo()
    {
        $timeStart = microtime(true);
        $countRequest = new NodeInfoRequest($this->serializer);

        /** @var NodeInfoResponse $response */
        $response = $this->client->send($countRequest);

        echo 'nodeInfo: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertNotEmpty($response->getName());
        $this->assertNotEmpty($response->getTagline());
        $this->assertSame(200, $response->getStatus());
        $this->assertArrayHasKey('number', $response->getVersion());
    }

    public function testDeleteIndex()
    {
        $this->createIndex();

        $timeStart = microtime(true);

        $createIndexRequest = new DeleteIndexRequest(self::INDEX, null, $this->serializer);

        /** @var IndexResponse $response */
        $response = $this->client->send($createIndexRequest);

        echo 'deleteIndex: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($response->acknowledged());
    }

    public function testCreateIndex()
    {
        $timeStart = microtime(true);

        $settings = array(
            'settings' => array(
                'index' => array(
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                )
            ),
            'mappings' => array(
                'test-type' => array(
                    '_source' => array('enabled' => false),
                    'properties' => array(
                        'test-field' => array(
                            'type' => 'string',
                            'index' => 'not_analyzed'
                        )
                    )
                )
            )
        );

        $createIndexRequest = new CreateIndexRequest(self::INDEX, null, $this->serializer);
        $createIndexRequest->setBody($settings);

        /** @var IndexResponse $response */
        $response = $this->client->send($createIndexRequest);

        echo 'createIndex: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($response->acknowledged());
    }

    public function testRefreshIndex()
    {
        $this->createIndex();
        $this->createDocument();
        $timeStart = microtime(true);

        $refreshIndexRequest = new RefreshIndexRequest(self::INDEX, null, $this->serializer);

        /** @var RefreshIndexResponse $response */
        $response = $this->client->send($refreshIndexRequest);

        echo 'refreshIndex: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;


        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
    }

    public function testIndexExists()
    {
        $this->createIndex();
        $this->refreshIndex();

        $timeStart = microtime(true);

        $indexExistsRequest = new IndexExistsRequest(self::INDEX, null, $this->serializer);

        /** @var ResponseInterface $response */
        $response = $this->client->send($indexExistsRequest);

        echo 'indexExists: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
    }


























//
//    public function testCreateDocument()
//    {
//        $this->createIndex();
//        $timeStart = microtime(true);
//
//        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//
//        $createDocumentRequest->setBody($data);
//        /** @var CreateUpdateDocumentResponse $response */
//        $response = $this->client->send($createDocumentRequest);
//
//        echo 'createDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertSame(self::INDEX, $response->getIndex());
//        $this->assertSame(self::TYPE, $response->getType());
//        $this->assertSame(1, $response->getVersion());
//        $this->assertTrue($response->isOk());
//        $this->assertTrue(strlen($response->getId()) > 5);
//    }
//
//    public function testGetDocument()
//    {
//        $this->createIndex();
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $id = $this->createDocument($data);
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//        $getDocumentRequest->setId($id);
//
//        /** @var DocumentResponse $getDocumentResponse */
//        $getDocumentResponse = $this->client->send($getDocumentRequest);
//
//        echo 'getDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($getDocumentResponse->exists());
//        $this->assertSame($id, $getDocumentResponse->getId());
//        $this->assertSame(1, $getDocumentResponse->getVersion());
//        $this->assertSame(self::INDEX, $getDocumentResponse->getIndex());
//        $this->assertSame(self::TYPE, $getDocumentResponse->getType());
//        $this->assertSame($data['name'], $getDocumentResponse->getSource()['name']);
//        $this->assertSame($data['value'], $getDocumentResponse->getSource()['value']);
//    }
//
//    public function testGetDocumentMissingDoc()
//    {
//        $this->createIndex();
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $this->createDocument($data);
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//        $getDocumentRequest->setId('notExisting');
//
//        try {
//            $this->client->send($getDocumentRequest);
//        } catch (ClientException $exception) {
//            $this->assertContains('Not Found', $exception->getMessage());
//            echo 'getDocumentMissingDoc: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//            return;
//        }
//
//        $this->fail();
//    }
//
//    public function testUpdateDocument()
//    {
//        $this->createIndex();
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $id = $this->createDocument($data);
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $updateDocumentRequest = new UpdateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//        $updateDocumentRequest->setId($id);
//        $data['name'] = 'testName';
//        $updateDocumentRequest->setBody($data);
//
//        /** @var CreateUpdateDocumentResponse $updateDocumentResponse */
//        $updateDocumentResponse = $this->client->send($updateDocumentRequest);
//
//        echo 'updateDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertSame(self::INDEX, $updateDocumentResponse->getIndex());
//        $this->assertSame(self::TYPE, $updateDocumentResponse->getType());
//        $this->assertSame(2, $updateDocumentResponse->getVersion());
//        $this->assertTrue($updateDocumentResponse->isOk());
//        $this->assertSame($id, $updateDocumentResponse->getId());
//    }
//
//    public function testDeleteDocument()
//    {
//        $this->createIndex();
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $id = $this->createDocument($data);
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $deleteDocumentRequest = new DeleteDocumentRequest(self::INDEX, self::TYPE, $this->serializer);;
//        $deleteDocumentRequest->setId($id);
//
//        $this->client->send($deleteDocumentRequest);
//
//        echo 'deleteDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//        $getDocumentRequest->setId($id);
//
//        try {
//            $this->client->send($getDocumentRequest);
//        } catch (ClientException $exception) {
//            $this->assertContains('Not Found', $exception->getMessage());
//
//            return;
//        }
//
//        $this->fail();
//    }
//
//    public function testMatchAllSearch()
//    {
//        $this->createIndex();
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $this->createDocument($data);
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $this->createDocument($data);
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $this->createDocument($data);
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $this->createDocument($data);
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//        $searchRequest = new SearchRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        $query = [
//            "query" => [
//                "match_all" => []
//            ]
//        ];
//
//        $searchRequest->setBody($query);
//        /** @var SearchResponse $response */
//        $response = $this->client->send($searchRequest);
//
//        echo 'search: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertGreaterThan(0, $response->took());
//        $this->assertFalse($response->timedOut());
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//        $this->assertGreaterThan(2, $response->totalHits());
//        $this->assertGreaterThan(0, $response->maxScoreHits());
//
//        $hits = $response->getHits();
//        $this->assertArrayHasKey('total', $hits);
//        $this->assertArrayHasKey('max_score', $hits);
//        $this->assertArrayHasKey('hits', $hits);
//
//        $hitsHits = $response->getHitsHits();
//        foreach ($hitsHits as $hit) {
//            $this->assertSame(self::INDEX, $hit['_index']);
//            $this->assertSame(self::TYPE, $hit['_type']);
//        }
//    }
//
//    public function testGetMappingWithType()
//    {
//        $this->createIndex();
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $this->createDocument($data);
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $getMappingRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        /** @var ResponseInterface $response */
//        $response = $this->client->send($getMappingRequest);
//
//        echo 'getMapping: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertContains(self::TYPE, $response->getRawData());
//        $this->assertContains('properties', $response->getRawData());
//    }
//
//    public function testGetMappingWithoutType()
//    {
//        $this->createIndex();
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//        $this->createDocument($data);
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $getMappingRequest = new GetMappingRequest(self::INDEX, null, $this->serializer);
//
//        /** @var ResponseInterface $response */
//        $response = $this->client->send($getMappingRequest);
//
//        echo 'getMapping(without type): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertContains(self::INDEX, $response->getRawData());
//        $this->assertContains(self::TYPE, $response->getRawData());
//        $this->assertContains('properties', $response->getRawData());
//    }
//

//
//    public function testIndexExistsNotExisting()
//    {
//        $this->createIndex();
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $indexExistsRequest = new IndexExistsRequest('not-existing-index', null, $this->serializer);
//
//        try {
//            $this->client->send($indexExistsRequest);
//        } catch(ClientException $exception) {
//            echo 'indexExists(not existing): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//            $this->assertContains('Not Found', $exception->getMessage());
//            return;
//        }
//
//        $this->fail();
//    }
//
//    public function testIndexTypeExists()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $indexExistsRequest = new IndexTypeExistsRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        /** @var ResponseInterface $response */
//        $response = $this->client->send($indexExistsRequest);
//
//        echo 'indexTypeExists: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
//    }
//
//    public function testIndexTypeExistsNotExisting()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $indexExistsRequest = new IndexTypeExistsRequest(self::INDEX, 'not-existing-type', $this->serializer);
//
//        try {
//            $this->client->send($indexExistsRequest);
//        } catch(ClientException $exception) {
//            echo 'indexTypeExists(not existing): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//            $this->assertContains('Not Found', $exception->getMessage());
//            return;
//        }
//
//        $this->fail();
//    }
//
//    public function testIndexStatsWithIndex()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//
//        $timeStart = microtime(true);
//
//        $indexStatsRequest = new IndexStatsRequest(self::INDEX, null, $this->serializer);
//
//        /** @var IndexStatsResponse $response */
//        $response = $this->client->send($indexStatsRequest);
//
//        echo 'indexStats(with index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//
//        $all = $response->getAll();
//        $this->assertTrue(isset($all['primaries']));
//        $this->assertTrue(isset($all['total']));
//
//        $indices = $response->getIndices();
//        $this->assertTrue(isset($indices[self::INDEX]));
//    }
//
//    public function testIndexStatsWithoutIndex()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//
//        $timeStart = microtime(true);
//
//        $indexStatsRequest = new IndexStatsRequest(null, null, $this->serializer);
//
//        /** @var IndexStatsResponse $response */
//        $response = $this->client->send($indexStatsRequest);
//
//        echo 'indexStats(without index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//
//        $all = $response->getAll();
//        $this->assertTrue(isset($all['primaries']));
//        $this->assertTrue(isset($all['total']));
//
//        $indices = $response->getIndices();
//        $this->assertTrue(isset($indices[self::INDEX]));
//    }
//
//    public function testIndexStatusWithIndex()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//
//        $timeStart = microtime(true);
//
//        $indexStatsRequest = new IndexStatusRequest(self::INDEX, null, $this->serializer);
//
//        /** @var IndexStatusResponse $response */
//        $response = $this->client->send($indexStatsRequest);
//
//        echo 'indexStatus(with index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//
//        $indices = $response->getIndices();
//        $this->assertTrue(isset($indices[self::INDEX]));
//    }
//
//    public function testIndexStatusWithoutIndex()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//
//        $timeStart = microtime(true);
//
//        $indexStatsRequest = new IndexStatusRequest(null, null, $this->serializer);
//
//        /** @var IndexStatusResponse $response */
//        $response = $this->client->send($indexStatsRequest);
//
//        echo 'indexStatus(with index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//
//        $indices = $response->getIndices();
//        $this->assertTrue(isset($indices[self::INDEX]));
//    }
//
//    public function testCreateMappingWithIndexAndType()
//    {
//        $this->createIndex();
//
//        $timeStart = microtime(true);
//
//        $mapping = [
//            self::TYPE => [
//                'properties' => [
//                    'message' => ['type' => 'string']
//                ]
//            ]
//        ];
//
//        $createMappingRequest = new CreateMappingRequest(self::INDEX , self::TYPE, $this->serializer);
//        $createMappingRequest->setBody($mapping);
//
//        /** @var IndexResponse $response */
//        $response = $this->client->send($createMappingRequest);
//
//        echo 'createMapping(with index,type): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//        $this->assertTrue($response->acknowledged());
//
//        //check if exists
//        $getMappingRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        /** @var ResponseInterface $getMappingResponse */
//        $getMappingResponse = $this->client->send($getMappingRequest);
//        $data = $getMappingResponse->getData();
//
//        $this->assertTrue(isset($data[self::TYPE]));
//        $this->assertTrue(isset($data[self::TYPE]['properties']));
//        $this->assertTrue(isset($data[self::TYPE]['properties']['message']));
//        $this->assertTrue(isset($data[self::TYPE]['properties']['message']['type']));
//        $this->assertSame('string', $data[self::TYPE]['properties']['message']['type']);
//        //the not activated assertSame is for tessting it when Gateway is fixed.
////        $this->assertSame($mapping[self::TYPE], $data[self::TYPE]);
//    }
//
//    public function testDeleteMappingWithIndexAndType()
//    {
//        $this->createIndex();
//        $mapping = [
//            self::TYPE => [
//                'properties' => [
//                    'message' => ['type' => 'string']
//                ]
//            ]
//        ];
//
//        $createMappingRequest = new CreateMappingRequest(self::INDEX , self::TYPE, $this->serializer);
//        $createMappingRequest->setBody($mapping);
//
//        $this->client->send($createMappingRequest);
//
//        $timeStart = microtime(true);
//
//        $deleteMappingRequest = new DeleteMappingRequest(self::INDEX , self::TYPE, $this->serializer);
//
//        /** @var IndexResponse $response */
//        $response = $this->client->send($deleteMappingRequest);
//
//        $this->assertTrue($response->isOk());
//        $this->assertTrue($response->acknowledged());
//
//        echo 'deleteMapping(with index,type): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        //check if exists
//        $getMappingRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        try {
//            $this->client->send($getMappingRequest);
//        } catch (ClientException $exception) {
//            $this->assertContains('Not Found', $exception->getMessage());
//            return;
//        }
//
//        $this->fail();
//    }
//
//    public function testIndexSettingsWithIndex()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//
//        $timeStart = microtime(true);
//
//        $indexStatsRequest = new IndexSettingsRequest(self::INDEX, null, $this->serializer);
//
//        /** @var IndexStatsResponse $response */
//        $response = $this->client->send($indexStatsRequest);
//
//        echo 'indexStats(with index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $data = $response->getData()->getGatewayValue();
//        $this->assertArrayHasKey(self::INDEX, $data);
//    }
//
//    public function testIndexSegmentsWithIndex()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $indexStatsRequest = new IndexSegmentsRequest(self::INDEX, null, $this->serializer);
//
//        /** @var IndexStatusResponse $response */
//        $response = $this->client->send($indexStatsRequest);
//
//        echo 'indexSegments(with index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//
//        $indices = $response->getIndices();
//        $this->assertTrue(isset($indices[self::INDEX]));
//    }
//
//    public function testIndexSegmentsWithoutIndex()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//
//        $indexStatsRequest = new IndexSegmentsRequest(null, null, $this->serializer);
//
//        /** @var IndexStatusResponse $response */
//        $response = $this->client->send($indexStatsRequest);
//
//        echo 'indexSegments(without index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//
//        $indices = $response->getIndices();
//        $this->assertTrue(isset($indices[self::INDEX]));
//    }
//
//    public function testClearCache()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $timeStart = microtime(true);
//
//        $refreshIndexRequest = new CacheClearRequest(self::INDEX, null, $this->serializer);
//
//        /** @var RefreshIndexResponse $response */
//        $response = $this->client->send($refreshIndexRequest);
//
//        echo 'clearCache: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//    }
//
//    public function testIndexOptimize()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $timeStart = microtime(true);
//
//        $refreshIndexRequest = new IndexOptimizeRequest(self::INDEX, null, $this->serializer);
//
//        /** @var RefreshIndexResponse $response */
//        $response = $this->client->send($refreshIndexRequest);
//
//        echo 'indexOptimize: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//    }
//
//    public function testIndexFlush()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $timeStart = microtime(true);
//
//        $refreshIndexRequest = new IndexFlushRequest(self::INDEX, null, $this->serializer);
//
//        /** @var RefreshIndexResponse $response */
//        $response = $this->client->send($refreshIndexRequest);
//
//        echo 'indexFlush: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//    }
//
//    public function testAliases()
//    {
//        $this->createIndex();
//        $this->refreshIndex();
//
//        $aliases = [
//            'actions' => [
//                [
//                    'add' => [
//                        'index' => self::INDEX,
//                        'alias' => 'alias-' . self::INDEX
//                    ]
//                ]
//            ]
//        ];
//
//        $timeStart = microtime(true);
//
//        $aliasesRequest = new AliasesRequest(null, null, $this->serializer);
//        $aliasesRequest->setBody($aliases);
//
//        /** @var IndexResponse $response */
//        $response = $this->client->send($aliasesRequest);
//
//        echo 'aliases: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($response->isOk());
//        $this->assertTrue($response->acknowledged());
//    }
//
//    public function testGetAliasesWithoutIndex()
//    {
//        $this->createIndex();
//
//        $aliases = [
//            'actions' => [
//                [
//                    'add' => [
//                        'index' => self::INDEX,
//                        'alias' => 'alias-' . self::INDEX
//                    ]
//                ]
//            ]
//        ];
//
//        $aliasesRequest = new AliasesRequest(null, null, $this->serializer);
//        $aliasesRequest->setBody($aliases);
//
//        /** @var IndexResponse $response */
//        $response = $this->client->send($aliasesRequest);
//
//        $timeStart = microtime(true);
//
//        $getAliasesRequest = new GetAliasesRequest(null, null, $this->serializer);
//
//        /** @var Response $response */
//        $response = $this->client->send($getAliasesRequest);
//
//        echo 'getAliases (without index): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $data = $response->getData()->getGatewayValue();
//
//        $this->assertArrayHasKey(self::INDEX, $data);
//        $this->assertTrue(isset($data[self::INDEX]['aliases']['alias-' . self::INDEX]));
//    }
//
//    public function testCreateDeleteTemplate()
//    {
//        $templateName = 'test-template';
//        $template = [
//            'template' => "te*",
//            'settings' => [
//                "number_of_shards" => 1
//            ],
//            'mappings' => [
//                'type1' => [
//                    '_source' => [ "enabled" => false ]
//                ]
//            ]
//        ];
//
//        $timeStart = microtime(true);
//
//        $createTemplateRequest = new CreateTemplateRequest($templateName, $this->serializer);
//        $createTemplateRequest->setBody($template);
//
//        /** @var IndexResponse $createResponse */
//        $createResponse = $this->client->send($createTemplateRequest);
//
//        echo 'createTemplate: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($createResponse->isOk());
//        $this->assertTrue($createResponse->acknowledged());
//
//        $timeStart = microtime(true);
//
//        $deleteTemplateRequest = new DeleteTemplateRequest($templateName, $this->serializer);
//
//        /** @var IndexResponse $deleteResponse */
//        $deleteResponse = $this->client->send($deleteTemplateRequest);
//
//        echo 'deleteTemplate: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($deleteResponse->isOk());
//        $this->assertTrue($deleteResponse->acknowledged());
//    }
//
//    public function testGetTemplate()
//    {
//        $templateName = 'test-template';
//        $template = [
//            'template' => "te*",
//            'settings' => [
//                "number_of_shards" => 1
//            ],
//            'mappings' => [
//                'type1' => [
//                    '_source' => [ "enabled" => false ]
//                ]
//            ]
//        ];
//
//        $createTemplateRequest = new CreateTemplateRequest($templateName, $this->serializer);
//        $createTemplateRequest->setBody($template);
//
//        /** @var IndexResponse $createResponse */
//        $createResponse = $this->client->send($createTemplateRequest);
//
//        $this->assertTrue($createResponse->isOk());
//        $this->assertTrue($createResponse->acknowledged());
//
//        $timeStart = microtime(true);
//
//        $getTemplateRequest = new GetTemplateRequest($templateName, $this->serializer);
//
//        $getResponse = $this->client->send($getTemplateRequest);
//
//        echo 'getTemplate: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->arrayHasKey($templateName, $getResponse->getData()->getGatewayValue());
//
//        $deleteTemplateRequest = new DeleteTemplateRequest($templateName, $this->serializer);
//
//        /** @var IndexResponse $deleteResponse */
//        $deleteResponse = $this->client->send($deleteTemplateRequest);
//
//        $this->assertTrue($deleteResponse->isOk());
//        $this->assertTrue($deleteResponse->acknowledged());
//    }
//
//    public function testCreateDeleteWarmer()
//    {
//        $index = 'warmer-index';
//
//        if(!$this->hasIndex($index)) {
//            $this->createIndex($index);
//        }
//
//        $this->refreshIndex($index);
//        sleep(1);
//
//        $warmerName = 'test_warmer';
//
//        $warmer = [
//            'query' => [
//                'match_all' => []
//            ]
//        ];
//
//        $timeStart = microtime(true);
//
//        $createWarmerRequest = new CreateWarmerRequest($index, null, $this->serializer);
//        $createWarmerRequest->setWarmerName($warmerName);
//        $createWarmerRequest->setBody($warmer);
//
//        /** @var IndexResponse $createResponse */
//        $createResponse = $this->client->send($createWarmerRequest);
//
//        echo 'createWarmer: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($createResponse->isOk());
//        $this->assertTrue($createResponse->acknowledged());
//
//        $this->refreshIndex($index);
//        sleep(1);
//
//        $timeStart = microtime(true);
//
//        $deleteRequest = new DeleteWarmerRequest($index, null, $this->serializer);
//        $deleteRequest->setWarmerName($warmerName);
//
//        $deleteResponse = $this->client->send($deleteRequest);
//
//        echo 'deleteWarmer: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($deleteResponse->isOk());
//        $this->assertTrue($deleteResponse->acknowledged());
//
//        $this->deleteIndex($index);
//    }
//
//    public function testGetWarmer()
//    {
//        $index = 'warmer-index';
//
//        if(!$this->hasIndex($index)) {
//            $this->createIndex($index);
//        }
//
//        $this->refreshIndex($index);
//        sleep(1);
//
//        $warmerName = 'test_warmer';
//
//        $warmer = [
//            'query' => [
//                'match_all' => []
//            ]
//        ];
//
//        $timeStart = microtime(true);
//
//        $createWarmerRequest = new CreateWarmerRequest($index, null, $this->serializer);
//        $createWarmerRequest->setWarmerName($warmerName);
//        $createWarmerRequest->setBody($warmer);
//
//        /** @var IndexResponse $createResponse */
//        $createResponse = $this->client->send($createWarmerRequest);
//
//        echo 'createWarmer: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($createResponse->isOk());
//        $this->assertTrue($createResponse->acknowledged());
//
//        $this->refreshIndex($index);
//
//        $getWarmerRequest = new GetWarmerRequest($index, null, $this->serializer);
//        $getWarmerRequest->setWarmerName($warmerName);
//
//        $getResponse = $this->client->send($getWarmerRequest);
//        $data = $getResponse->getData()->getGatewayValue();
//        $this->assertArrayHasKey($warmerName, $data['warmer-index']['warmers']);
//
//        sleep(1);
//
//        $timeStart = microtime(true);
//
//        $deleteRequest = new DeleteWarmerRequest($index, null, $this->serializer);
//        $deleteRequest->setWarmerName($warmerName);
//
//        $deleteResponse = $this->client->send($deleteRequest);
//
//        echo 'deleteWarmer: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($deleteResponse->isOk());
//        $this->assertTrue($deleteResponse->acknowledged());
//
//        $this->deleteIndex($index);
//    }
//
//    public function testCount()
//    {
//        $this->createIndex();
//        $this->createDocument();
//        $this->createDocument();
//        $this->createDocument();
//        $this->refreshIndex();
//
//        $timeStart = microtime(true);
//        $countRequest = new CountRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        /** @var CountResponse $response */
//        $response = $this->client->send($countRequest);
//
//        echo 'count: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertSame(3, $response->getCount());
//
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//    }

    private function createIndex($index = null)
    {
        if(null === $index) {
            $index = self::INDEX;
        }

        $createIndexRequest = new CreateIndexRequest($index, null, $this->serializer);
        $this->client->send($createIndexRequest);
    }

    private function hasIndex($index = null)
    {
        if(null === $index) {
            $index = self::INDEX;
        }

        $indexExistsRequest = new IndexExistsRequest($index, null, $this->serializer);
        try {
            $this->client->send($indexExistsRequest);
            return true;
        } catch(ClientException $exception) {
            return false;
        }

    }

    private function deleteIndex($index = null)
    {
        if(null === $index) {
            $index = self::INDEX;
        }

        $deleteIndexRequest = new DeleteIndexRequest($index, null, $this->serializer);
        $this->client->send($deleteIndexRequest);
    }

    private function refreshIndex($index = null)
    {
        if(null === $index) {
            $index = self::INDEX;
        }

        $refreshIndexRequest = new RefreshIndexRequest($index, null, $this->serializer);
        $this->client->send($refreshIndexRequest);
    }

    private function createDocument($data = null)
    {
        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        if(null === $data) {
            $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        }

        $createDocumentRequest->setBody($data);
        /** @var CreateUpdateDocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);

        return $response->getId();
    }
}

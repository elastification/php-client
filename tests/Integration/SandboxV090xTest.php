<?php
namespace Elastification\Client\Tests\Integration;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\V090x\CreateDocumentRequest;
use Elastification\Client\Request\V090x\DeleteDocumentRequest;
use Elastification\Client\Request\V090x\GetDocumentRequest;
use Elastification\Client\Request\V090x\GetMappingRequest;
use Elastification\Client\Request\V090x\Index\CreateIndexRequest;
use Elastification\Client\Request\V090x\Index\DeleteIndexRequest;
use Elastification\Client\Request\V090x\Index\IndexExistsRequest;
use Elastification\Client\Request\V090x\SearchRequest;
use Elastification\Client\Request\V090x\UpdateDocumentRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Response\V090x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V090x\DocumentResponse;
use Elastification\Client\Response\V090x\Index\IndexResponse;
use Elastification\Client\Response\V090x\SearchResponse;
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
class SandboxV090xTest extends \PHPUnit_Framework_TestCase
{

    const INDEX = 'dawen-elastic';
    const TYPE = 'sandbox';

    private $url = 'http://localhost:9200/';

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


    }

    public function testDeleteIndex()
    {
        $this->createIndex();

        $timeStart = microtime(true);

        $createIndexRequest = new DeleteIndexRequest(self::INDEX, null, $this->serializer);

        /** @var IndexResponse $response */
        $response = $this->client->send($createIndexRequest);

        echo 'deleteIndex: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($response->isOk());
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

        $this->assertTrue($response->isOk());
        $this->assertTrue($response->acknowledged());
    }

    public function testCreateDocument()
    {
        $this->createIndex();
        $timeStart = microtime(true);

        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $createDocumentRequest->setBody($data);
        /** @var CreateUpdateDocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);

        echo 'createDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);
    }

    public function testGetDocument()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $id = $this->createDocument($data);

        $timeStart = microtime(true);

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId($id);

        /** @var DocumentResponse $getDocumentResponse */
        $getDocumentResponse = $this->client->send($getDocumentRequest);

        echo 'getDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($getDocumentResponse->exists());
        $this->assertSame($id, $getDocumentResponse->getId());
        $this->assertSame(1, $getDocumentResponse->getVersion());
        $this->assertSame(self::INDEX, $getDocumentResponse->getIndex());
        $this->assertSame(self::TYPE, $getDocumentResponse->getType());
        $this->assertSame($data['name'], $getDocumentResponse->getSource()['name']);
        $this->assertSame($data['value'], $getDocumentResponse->getSource()['value']);
    }

    public function testGetDocumentMissingDoc()
    {
        $this->createIndex();sleep(5);

        $timeStart = microtime(true);

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId('notExisting');

        try {
            $this->client->send($getDocumentRequest);
        } catch (ClientException $exception) {
            $this->assertContains('Not Found', $exception->getMessage());
            echo 'getDocumentMissingDoc: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
            return;
        }

        $this->fail();
    }

//    public function testUpdateDocument()
//    {
//
//        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
//
//        $createDocumentRequest->setBody($data);
//        /** @var CreateUpdateDocumentResponse $response */
//        $response = $this->client->send($createDocumentRequest);
//
//
//        $this->assertSame(self::INDEX, $response->getIndex());
//        $this->assertSame(self::TYPE, $response->getType());
//        $this->assertSame(1, $response->getVersion());
//        $this->assertTrue($response->isOk());
//        $this->assertTrue(strlen($response->getId()) > 5);
//
//        $timeStart = microtime(true);
//
//        $updateDocumentRequest = new UpdateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//        $updateDocumentRequest->setId($response->getId());
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
//        $this->assertSame($response->getId(), $updateDocumentResponse->getId());
//    }
//
//    public function testDeleteDocument()
//    {
//        $timeStart = microtime(true);
//        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        $createDocumentRequest->setBody(
//            array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000))
//        );
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
//
//        $deleteDocumentRequest = new DeleteDocumentRequest(self::INDEX, self::TYPE, $this->serializer);;
//        $deleteDocumentRequest->setId($response->getId());
//
//        $this->client->send($deleteDocumentRequest);
//
//        echo 'deleteDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
//        $getDocumentRequest->setId($response->getId());
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
//        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeArrayGateway', $shards);
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
//        $createDocumentRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        $timeStart = microtime(true);
//
//        /** @var ResponseInterface $response */
//        $response = $this->client->send($createDocumentRequest);
//
//        echo 'getMapping: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertContains(self::TYPE, $response->getRawData());
//        $this->assertContains('properties', $response->getRawData());
//    }
//
//    public function testGetMappingWithoutType()
//    {
//        $createDocumentRequest = new GetMappingRequest(self::INDEX, null, $this->serializer);
//
//        $timeStart = microtime(true);
//
//        /** @var ResponseInterface $response */
//        $response = $this->client->send($createDocumentRequest);
//
//        echo 'getMapping: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertContains(self::INDEX, $response->getRawData());
//        $this->assertContains(self::TYPE, $response->getRawData());
//        $this->assertContains('properties', $response->getRawData());
//    }
//
//    public function testIndexExists()
//    {
//        $indexExistsRequest = new IndexExistsRequest(self::INDEX, null, $this->serializer);
//
//        $timeStart = microtime(true);
//
//        /** @var ResponseInterface $response */
//        $response = $this->client->send($indexExistsRequest);
//
//        echo 'indexExists: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
//    }
//
//    public function testIndexExistsNotExisting()
//    {
//        $indexExistsRequest = new IndexExistsRequest('not-existing-index', null, $this->serializer);
//
//        $timeStart = microtime(true);
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

    private function createIndex()
    {
        $createIndexRequest = new CreateIndexRequest(self::INDEX, null, $this->serializer);
        $this->client->send($createIndexRequest);
    }

    private function hasIndex()
    {
        $indexExistsRequest = new IndexExistsRequest(self::INDEX, null, $this->serializer);
        try {
            $this->client->send($indexExistsRequest);
            return true;
        } catch(ClientException $exception) {
            return false;
        }

    }

    private function deleteIndex()
    {
        $deleteIndexRequest = new DeleteIndexRequest(self::INDEX, null, $this->serializer);
        $this->client->send($deleteIndexRequest);
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

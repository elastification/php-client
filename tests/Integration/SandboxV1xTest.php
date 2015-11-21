<?php
namespace Elastification\Client\Tests\Integration;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\V1x\AliasesRequest;
use Elastification\Client\Request\V1x\CountRequest;
use Elastification\Client\Request\V1x\CreateDocumentRequest;
use Elastification\Client\Request\V1x\CreateTemplateRequest;
use Elastification\Client\Request\V1x\DeleteDocumentRequest;
use Elastification\Client\Request\V1x\DeleteTemplateRequest;
use Elastification\Client\Request\V1x\GetDocumentRequest;
use Elastification\Client\Request\V1x\GetTemplateRequest;
use Elastification\Client\Request\V1x\Index\BulkUpdateIndexRequest;
use Elastification\Client\Request\V1x\Index\CacheClearRequest;
use Elastification\Client\Request\V1x\Index\CreateIndexRequest;
use Elastification\Client\Request\V1x\Index\CreateMappingRequest;
use Elastification\Client\Request\V1x\Index\CreateWarmerRequest;
use Elastification\Client\Request\V1x\Index\DeleteIndexRequest;
use Elastification\Client\Request\V1x\Index\DeleteMappingRequest;
use Elastification\Client\Request\V1x\Index\DeleteWarmerRequest;
use Elastification\Client\Request\V1x\Index\GetAliasesRequest;
use Elastification\Client\Request\V1x\Index\GetMappingRequest;
use Elastification\Client\Request\V1x\Index\GetWarmerRequest;
use Elastification\Client\Request\V1x\Index\IndexExistsRequest;
use Elastification\Client\Request\V1x\Index\IndexFlushRequest;
use Elastification\Client\Request\V1x\Index\IndexOptimizeRequest;
use Elastification\Client\Request\V1x\Index\IndexSegmentsRequest;
use Elastification\Client\Request\V1x\Index\IndexSettingsRequest;
use Elastification\Client\Request\V1x\Index\IndexStatsRequest;
use Elastification\Client\Request\V1x\Index\IndexStatusRequest;
use Elastification\Client\Request\V1x\Index\IndexTypeExistsRequest;
use Elastification\Client\Request\V1x\Index\RefreshIndexRequest;
use Elastification\Client\Request\V1x\NodeInfoRequest;
use Elastification\Client\Request\V1x\SearchRequest;
use Elastification\Client\Request\V1x\SearchScrollRequest;
use Elastification\Client\Request\V1x\UpdateDocumentRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Response\V1x\CountResponse;
use Elastification\Client\Response\V1x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V1x\DeleteDocumentResponse;
use Elastification\Client\Response\V1x\DocumentResponse;
use Elastification\Client\Response\V1x\Index\IndexResponse;
use Elastification\Client\Response\V1x\Index\IndexStatsResponse;
use Elastification\Client\Response\V1x\Index\IndexStatusResponse;
use Elastification\Client\Response\V1x\Index\RefreshIndexResponse;
use Elastification\Client\Response\V1x\NodeInfoResponse;
use Elastification\Client\Response\V1x\SearchResponse;
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

    private $url = 'http://192.168.33.144:9200/';

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

        $this->guzzleClient = new GuzzleClient(array('base_uri' => $this->url));
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
//        $this->assertTrue($response->acknowledged());
//
//        $this->refreshIndex();
//
//        //check if exists
//        $getMappingRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        /** @var ResponseInterface $getMappingResponse */
//        $getMappingResponse = $this->client->send($getMappingRequest);
//        $data = $getMappingResponse->getData();
//
//        $this->assertTrue(isset($data[self::INDEX]));
//        $mappings = $data[self::INDEX]['mappings'];
//        $this->assertTrue(isset($mappings[self::TYPE]['properties']));
//        $this->assertTrue(isset($mappings[self::TYPE]['properties']['message']));
//        $this->assertTrue(isset($mappings[self::TYPE]['properties']['message']['type']));
//        $this->assertSame('string', $mappings[self::TYPE]['properties']['message']['type']);
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
//        $this->assertTrue($response->acknowledged());
//
//        echo 'deleteMapping(with index,type): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        //check if exists
//        $getMappingRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        $response = $this->client->send($getMappingRequest);
//        $this->assertSame('{}', $response->getRawData());
//    }
//
//
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
//        $shards = $response->getShards();
//        $this->assertTrue(isset($shards['total']));
//        $this->assertTrue(isset($shards['successful']));
//        $this->assertTrue(isset($shards['failed']));
//
//        $indices = $response->getIndices();
//        $this->assertTrue(isset($indices[self::INDEX]));
//    }
//
//
//
//
//
//
//

//
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
//        /** @var IndexResponse $deleteResponse */
//        $deleteResponse = $this->client->send($deleteRequest);
//
//        echo 'deleteWarmer: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
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
//        $this->assertTrue($createResponse->acknowledged());
//
//        $this->refreshIndex($index);
//
//        $timeStart = microtime(true);
//
//        $getWarmerRequest = new GetWarmerRequest($index, null, $this->serializer);
//        $getWarmerRequest->setWarmerName($warmerName);
//
//        echo 'getWarmer: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
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
//        /** @var IndexResponse $deleteResponse */
//        $deleteResponse = $this->client->send($deleteRequest);
//
//        echo 'deleteWarmer: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertTrue($deleteResponse->acknowledged());
//
//        $this->deleteIndex($index);
//    }
//




















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

<?php
namespace Elastification\Client\Tests\Integration;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\V090x\AliasesRequest;
use Elastification\Client\Request\V090x\CountRequest;
use Elastification\Client\Request\V090x\CreateDocumentRequest;
use Elastification\Client\Request\V090x\DeleteByQueryRequest;
use Elastification\Client\Request\V090x\DeleteDocumentRequest;
use Elastification\Client\Request\V090x\DeleteSearchRequest;
use Elastification\Client\Request\V090x\DeleteTemplateRequest;
use Elastification\Client\Request\V090x\GetDocumentRequest;
use Elastification\Client\Request\V090x\GetTemplateRequest;
use Elastification\Client\Request\V090x\Index\CacheClearRequest;
use Elastification\Client\Request\V090x\Index\CreateIndexRequest;
use Elastification\Client\Request\V090x\Index\CreateMappingRequest;
use Elastification\Client\Request\V090x\CreateTemplateRequest;
use Elastification\Client\Request\V090x\Index\CreateWarmerRequest;
use Elastification\Client\Request\V090x\Index\DeleteIndexRequest;
use Elastification\Client\Request\V090x\Index\DeleteMappingRequest;
use Elastification\Client\Request\V090x\Index\DeleteWarmerRequest;
use Elastification\Client\Request\V090x\Index\GetAliasesRequest;
use Elastification\Client\Request\V090x\Index\GetMappingRequest;
use Elastification\Client\Request\V090x\Index\GetWarmerRequest;
use Elastification\Client\Request\V090x\Index\IndexExistsRequest;
use Elastification\Client\Request\V090x\Index\IndexFlushRequest;
use Elastification\Client\Request\V090x\Index\IndexOptimizeRequest;
use Elastification\Client\Request\V090x\Index\IndexSegmentsRequest;
use Elastification\Client\Request\V090x\Index\IndexSettingsRequest;
use Elastification\Client\Request\V090x\Index\IndexStatsRequest;
use Elastification\Client\Request\V090x\Index\IndexStatusRequest;
use Elastification\Client\Request\V090x\Index\IndexTypeExistsRequest;
use Elastification\Client\Request\V090x\Index\RefreshIndexRequest;
use Elastification\Client\Request\V090x\Index\UpdateAliasesRequest;
use Elastification\Client\Request\V090x\NodeInfoRequest;
use Elastification\Client\Request\V090x\SearchRequest;
use Elastification\Client\Request\V090x\UpdateDocumentRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Response\V090x\CountResponse;
use Elastification\Client\Response\V090x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V090x\DocumentResponse;
use Elastification\Client\Response\V090x\Index\IndexResponse;
use Elastification\Client\Response\V090x\Index\IndexStatsResponse;
use Elastification\Client\Response\V090x\Index\IndexStatusResponse;
use Elastification\Client\Response\V090x\Index\RefreshIndexResponse;
use Elastification\Client\Response\V090x\NodeInfoResponse;
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

    private $url = 'http://127.0.01:9200/';
//    private $url = 'http://192.168.33.109:9200/';

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



    public function testCreateMappingWithIndexAndType()
    {
        $this->createIndex();

        $timeStart = microtime(true);

        $mapping = [
            self::TYPE => [
                'properties' => [
                    'message' => ['type' => 'string']
                ]
            ]
        ];

        $createMappingRequest = new CreateMappingRequest(self::INDEX , self::TYPE, $this->serializer);
        $createMappingRequest->setBody($mapping);

        /** @var IndexResponse $response */
        $response = $this->client->send($createMappingRequest);

        echo 'createMapping(with index,type): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($response->isOk());
        $this->assertTrue($response->acknowledged());

        //check if exists
        $getMappingRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);

        /** @var ResponseInterface $getMappingResponse */
        $getMappingResponse = $this->client->send($getMappingRequest);
        $data = $getMappingResponse->getData();

        $this->assertTrue(isset($data[self::TYPE]));
        $this->assertTrue(isset($data[self::TYPE]['properties']));
        $this->assertTrue(isset($data[self::TYPE]['properties']['message']));
        $this->assertTrue(isset($data[self::TYPE]['properties']['message']['type']));
        $this->assertSame('string', $data[self::TYPE]['properties']['message']['type']);
        //the not activated assertSame is for tessting it when Gateway is fixed.
//        $this->assertSame($mapping[self::TYPE], $data[self::TYPE]);
    }

    public function testDeleteMappingWithIndexAndType()
    {
        $this->createIndex();
        $mapping = [
            self::TYPE => [
                'properties' => [
                    'message' => ['type' => 'string']
                ]
            ]
        ];

        $createMappingRequest = new CreateMappingRequest(self::INDEX , self::TYPE, $this->serializer);
        $createMappingRequest->setBody($mapping);

        $this->client->send($createMappingRequest);

        $timeStart = microtime(true);

        $deleteMappingRequest = new DeleteMappingRequest(self::INDEX , self::TYPE, $this->serializer);

        /** @var IndexResponse $response */
        $response = $this->client->send($deleteMappingRequest);

        $this->assertTrue($response->isOk());
        $this->assertTrue($response->acknowledged());

        echo 'deleteMapping(with index,type): ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        //check if exists
        $getMappingRequest = new GetMappingRequest(self::INDEX, self::TYPE, $this->serializer);

        try {
            $this->client->send($getMappingRequest);
        } catch (ClientException $exception) {
            $this->assertContains('Not Found', $exception->getMessage());
            return;
        }

        $this->fail();
    }






























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

<?php
namespace Dawen\Component\Elastic\Tests\Integration;

use Dawen\Component\Elastic\Exception\ClientException;
use Dawen\Component\Elastic\Request\RequestManager;
use Dawen\Component\Elastic\Request\RequestManagerInterface;
use Dawen\Component\Elastic\Request\V090x\CreateDocumentRequest;
use Dawen\Component\Elastic\Request\V090x\DeleteDocumentRequest;
use Dawen\Component\Elastic\Request\V090x\GetDocumentRequest;
use Dawen\Component\Elastic\Request\V090x\SearchRequest;
use Dawen\Component\Elastic\Request\V090x\UpdateDocumentRequest;
use Dawen\Component\Elastic\Response\V090x\DocumentResponse;
use Dawen\Component\Elastic\Serializer\NativeJsonSerializer;
use Dawen\Component\Elastic\Serializer\SerializerInterface;
use Dawen\Component\Elastic\Transport\HttpGuzzle\GuzzleTransport;
use Dawen\Component\Elastic\Transport\TransportInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Dawen\Component\Elastic\Client;
use Dawen\Component\Elastic\ClientInterface;

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

        $this->guzzleClient = null;
        $this->requestManager = null;
        $this->client = null;
    }

    public function testCreateDocument()
    {
        $timeStart = microtime(true);
        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);

        $createDocumentRequest->setBody(array('name' => 'test'.rand(100,10000), 'value' => 'myTestVal'.rand(100,10000)));
        /** @var DocumentResponse $response */
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

        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);

        $data = array('name' => 'test'.rand(100,10000), 'value' => 'myTestVal'.rand(100,10000));

        $createDocumentRequest->setBody($data);
        /** @var DocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);


        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);

        $timeStart = microtime(true);

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId($response->getId());

        $getDocumentResponse = $this->client->send($getDocumentRequest);

        echo 'getDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($getDocumentResponse->exists());
        $this->assertSame($response->getId(), $getDocumentResponse->getId());
        $this->assertSame(1, $getDocumentResponse->getVersion());
        $this->assertSame(self::INDEX, $getDocumentResponse->getIndex());
        $this->assertSame(self::TYPE, $getDocumentResponse->getType());
        $this->assertSame($data, $getDocumentResponse->getSource());
    }

    public function testGetDocumentMissingDoc()
    {
        $timeStart = microtime(true);

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId('notExisting');

        try {
            $getDocumentResponse = $this->client->send($getDocumentRequest);
        } catch(ClientException $exception) {
            $this->assertContains('Not Found', $exception->getMessage());
            echo 'getDocumentMissingDoc: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function testUpdateDocument()
    {

        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);

        $data = array('name' => 'test'.rand(100,10000), 'value' => 'myTestVal'.rand(100,10000));

        $createDocumentRequest->setBody($data);
        /** @var DocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);


        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);

        $timeStart = microtime(true);

        $updateDocumentRequest = new UpdateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $updateDocumentRequest->setId($response->getId());
        $data['name'] = 'testName';
        $updateDocumentRequest->setBody($data);

        $updateDocumentResponse = $this->client->send($updateDocumentRequest);

        echo 'updateDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertSame(self::INDEX, $updateDocumentResponse->getIndex());
        $this->assertSame(self::TYPE, $updateDocumentResponse->getType());
        $this->assertSame(2, $updateDocumentResponse->getVersion());
        $this->assertTrue($updateDocumentResponse->isOk());
        $this->assertSame($response->getId(), $updateDocumentResponse->getId());
    }

    public function testDeleteDocument()
    {
        $timeStart = microtime(true);
        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);

        $createDocumentRequest->setBody(array('name' => 'test'.rand(100,10000), 'value' => 'myTestVal'.rand(100,10000)));
        /** @var DocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);

        echo 'createDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);

        $deleteDocumentRequest = new DeleteDocumentRequest(self::INDEX, self::TYPE, $this->serializer);;
        $deleteDocumentRequest->setId($response->getId());

        $deleteResponse = $this->client->send($deleteDocumentRequest);

        echo 'deleteDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId($response->getId());

        try {
            $getDocumentResponse = $this->client->send($getDocumentRequest);
        } catch(ClientException $exception) {
            $this->assertContains('Not Found', $exception->getMessage());

            return;
        }

        $this->fail();
    }

    public function testMatchAllSearch()
    {
        $timeStart = microtime(true);
        $searchRequest = new SearchRequest('dawen-elastic', 'sandbox', $this->serializer);

        $query = [
            "query" => [
                "match_all" => []
             ]
        ];

        $searchRequest->setBody($query);
        $response = $this->client->send($searchRequest);

        echo 'search: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertGreaterThan(0, $response->took());
        $this->assertFalse($response->timedOut());
        $shards = $response->getShards();
        $this->assertTrue(is_array($shards));
        $this->assertArrayHasKey('total', $shards);
        $this->assertArrayHasKey('successful', $shards);
        $this->assertArrayHasKey('failed', $shards);
        $this->assertEquals($shards['total'], $shards['successful']);
        $this->assertSame(0, $shards['failed']);
        $this->assertGreaterThan(2, $response->totalHits());
        $this->assertGreaterThan(0, $response->maxScoreHits());
        $this->assertGreaterThan(2, $response->getHitsHits());
        $hits = $response->getHits();
        $this->assertTrue(is_array($hits));
        $this->assertArrayHasKey('total', $hits);
        $this->assertArrayHasKey('max_score', $hits);
        $this->assertArrayHasKey('hits', $hits);
    }
}

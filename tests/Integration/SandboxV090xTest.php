<?php
namespace Elastification\Client\Tests\Integration;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\V090x\CreateDocumentRequest;
use Elastification\Client\Request\V090x\DeleteDocumentRequest;
use Elastification\Client\Request\V090x\GetDocumentRequest;
use Elastification\Client\Request\V090x\UpdateDocumentRequest;
use Elastification\Client\Response\V090x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V090x\DocumentResponse;
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

        $this->guzzleClient = null;
        $this->requestManager = null;
        $this->client = null;
    }

    public function testCreateDocument()
    {
        $timeStart = microtime(true);
        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);

        $createDocumentRequest->setBody(array('name' => 'test'.rand(100,10000), 'value' => 'myTestVal'.rand(100,10000)));
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
        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $this->serializer);

        $data = array('name' => 'test'.rand(100,10000), 'value' => 'myTestVal'.rand(100,10000));

        $createDocumentRequest->setBody($data);
        /** @var CreateUpdateDocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);

        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);

        $timeStart = microtime(true);

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId($response->getId());

        /** @var DocumentResponse $getDocumentResponse */
        $getDocumentResponse = $this->client->send($getDocumentRequest);

        echo 'getDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($getDocumentResponse->exists());
        $this->assertSame($response->getId(), $getDocumentResponse->getId());
        $this->assertSame(1, $getDocumentResponse->getVersion());
        $this->assertSame(self::INDEX, $getDocumentResponse->getIndex());
        $this->assertSame(self::TYPE, $getDocumentResponse->getType());
        //todo think about iteratable
        $this->assertSame(1, count($getDocumentResponse->getSource()));
        $this->assertSame($data['name'], $getDocumentResponse->getSource()['name']);
        $this->assertSame($data['value'], $getDocumentResponse->getSource()['value']);
    }

    public function testGetDocumentMissingDoc()
    {
        $timeStart = microtime(true);

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId('notExisting');

        try {
            $this->client->send($getDocumentRequest);
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
        /** @var CreateUpdateDocumentResponse $response */
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

        /** @var CreateUpdateDocumentResponse $updateDocumentResponse */
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
        /** @var CreateUpdateDocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);

        echo 'createDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);

        $deleteDocumentRequest = new DeleteDocumentRequest(self::INDEX, self::TYPE, $this->serializer);;
        $deleteDocumentRequest->setId($response->getId());

        $this->client->send($deleteDocumentRequest);

        echo 'deleteDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $getDocumentRequest = new GetDocumentRequest(self::INDEX, self::TYPE, $this->serializer);
        $getDocumentRequest->setId($response->getId());

        try {
            $this->client->send($getDocumentRequest);
        } catch(ClientException $exception) {
            $this->assertContains('Not Found', $exception->getMessage());

            return;
        }

        $this->fail();
    }

//    public function testMatchAllSearch()
//    {
//        $timeStart = microtime(true);
//        $searchRequest = new SearchRequest(self::INDEX, self::TYPE, $this->serializer);
//
//        $query = [
//            "query" => [
//                "match_all" => []
//             ]
//        ];
//
//        $searchRequest->setBody($query);
//        $response = $this->client->send($searchRequest);
//
//        echo 'search: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;
//
//        $this->assertGreaterThan(0, $response->took());
//        $this->assertFalse($response->timedOut());
//        $shards = $response->getShards();
//        $this->assertInstanceOf('Elastification\Client\Serializer\Gateway\NativeArrayGateway', $shards);
//        $this->assertSame(1, count($shards));
//        $this->assertArrayHasKey('total', $shards);
//        $this->assertArrayHasKey('successful', $shards);
//        $this->assertArrayHasKey('failed', $shards);
//        $this->assertEquals($shards['total'], $shards['successful']);
//        $this->assertSame(0, $shards['failed']);
//        $this->assertGreaterThan(2, $response->totalHits());
//        $this->assertGreaterThan(0, $response->maxScoreHits());
//        $hitsHits = $response->getHitsHits();
//
//todo think about iteratable
//        var_dump($hitsHits[0]);
////        foreach($hitsHits as $keyHit => $hit) {
////            var_dump($keyHit);
////        }
//        die();
////        $this->assertTrue(count() >= 2);
//
//        $hits = $response->getHits();
//
//        var_dump($hits);die();
//        $this->assertTrue(is_array($hits));
//        $this->assertArrayHasKey('total', $hits);
//        $this->assertArrayHasKey('max_score', $hits);
//        $this->assertArrayHasKey('hits', $hits);
//    }
}

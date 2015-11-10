<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 18/12/14
 * Time: 11:47
 */

namespace Elastification\Client\Tests\Integration\Repository\V90x;

use Elastification\Client\ClientVersionMap;
use Elastification\Client\Repository\DocumentRepository;
use Elastification\Client\Repository\DocumentRepositoryInterface;
use Elastification\Client\Repository\RepositoryClassMapInterface;
use Elastification\Client\Request\V090x\Index\DeleteIndexRequest;
use Elastification\Client\Request\V090x\Index\IndexExistsRequest;
use Elastification\Client\Request\V090x\Index\RefreshIndexRequest;
use Elastification\Client\Serializer\NativeJsonSerializer;
use Elastification\Client\Serializer\SerializerInterface;
use Elastification\Client\Transport\HttpGuzzle\GuzzleTransport;
use Elastification\Client\Transport\TransportInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Elastification\Client\Client;
use Elastification\Client\ClientInterface;
use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;

class DocumentRepositoryV090xTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'dawen-elastic';
    const TYPE = 'repository';

    private $url = 'http://127.0.0.1:9200/';
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

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->guzzleClient = new GuzzleClient(array('base_url' => $this->url));
        $this->transportClient = new GuzzleTransport($this->guzzleClient);
        $this->requestManager = new RequestManager();
        $this->client = new Client($this->transportClient, $this->requestManager);
        $this->serializer = new NativeJsonSerializer();
        $this->documentRepository = new DocumentRepository($this->client,
            $this->serializer,
            null,
            ClientVersionMap::VERSION_V090X);
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
        $this->documentRepository = null;
    }


    public function testCreateDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $timeStart = microtime(true);
        $response = $this->documentRepository->create(self::INDEX, self::TYPE, $data);
        echo 'create: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);
    }

    public function testGetDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $createResponse = $this->documentRepository->create(self::INDEX, self::TYPE, $data);

        $timeStart = microtime(true);
        $getResponse = $this->documentRepository->get(self::INDEX, self::TYPE, $createResponse->getId());
        echo 'get: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($getResponse->exists());
        $this->assertSame($createResponse->getId(), $getResponse->getId());
        $this->assertSame(1, $getResponse->getVersion());
        $this->assertSame(self::INDEX, $getResponse->getIndex());
        $this->assertSame(self::TYPE, $getResponse->getType());
        $this->assertSame($data['name'], $getResponse->getSource()['name']);
        $this->assertSame($data['value'], $getResponse->getSource()['value']);
    }

    public function testDeleteDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $createResponse = $this->documentRepository->create(self::INDEX, self::TYPE, $data);
        $this->refreshIndex();

        $timeStart = microtime(true);

        echo 'delete: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->documentRepository->delete(self::INDEX, self::TYPE, $createResponse->getId());

        try {
            $this->documentRepository->get(self::INDEX, self::TYPE, $createResponse->getId());
        } catch (ClientException $exception) {
            $this->assertContains('Not Found', $exception->getMessage());

            return;
        }

        $this->fail();
    }

    public function testUpdateDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        $createResponse = $this->documentRepository->create(self::INDEX, self::TYPE, $data);
        $this->refreshIndex();

        $timeStart = microtime(true);

        $data['name'] = 'test3';
        $this->documentRepository->update(self::INDEX, self::TYPE, $createResponse->getId(), $data);

        echo 'update: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $getResponse = $this->documentRepository->get(self::INDEX, self::TYPE, $createResponse->getId());
        $storedData = $getResponse->getSource();
        $this->assertSame($data, $storedData);
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
}


<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 18/12/14
 * Time: 11:47
 */

namespace Elastification\Client\Tests\Integration;

use Elastification\Client\ClientVersionMap;
use Elastification\Client\Repository\DocumentRepository;
use Elastification\Client\Repository\DocumentRepositoryInterface;
use Elastification\Client\Repository\IndexRepository;
use Elastification\Client\Repository\IndexRepositoryInterface;
use Elastification\Client\Repository\RepositoryClassMapInterface;
use Elastification\Client\Repository\SearchRepository;
use Elastification\Client\Repository\SearchRepositoryInterface;
use Elastification\Client\Request\V090x\Index\DeleteIndexRequest;
use Elastification\Client\Request\V090x\Index\IndexExistsRequest;
use Elastification\Client\Request\V090x\Index\RefreshIndexRequest;
use Elastification\Client\Response\V090x\SearchResponse;
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

class IndexRepositoryV090xTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'dawen-elastic';
    const TYPE = 'index-repository';

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

    /**
     * @var IndexRepositoryInterface
     */
    private $indexRepository;

    /**
     * @var array
     */
    private $data = array(
        array('city' => 'Berlin', 'country' => 'Germany'),
        array('city' => 'Cologne', 'country' => 'Germany'),
        array('city' => 'Paris', 'country' => 'France'),
        array('city' => 'Barcelona', 'country' => 'Spain')
    );

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
        $this->indexRepository = new IndexRepository($this->client,
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
        $this->indexRepository = null;
    }


    public function testIndexExistsWithoutExisting()
    {
        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $result = $this->indexRepository->exists(self::INDEX);
        echo 'index exist without: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertFalse($result);
    }

    public function testIndexExistsWithExisting()
    {
        $this->createSampleData();

        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $result = $this->indexRepository->exists(self::INDEX);
        echo 'index exist with: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($result);
    }

    public function testIndexCreate()
    {
        $this->assertFalse($this->indexRepository->exists(self::INDEX));

        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $response = $this->indexRepository->create(self::INDEX);
        echo 'index create: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertTrue($this->indexRepository->exists(self::INDEX));
    }

    public function testIndexDelete()
    {
        $this->assertFalse($this->indexRepository->exists(self::INDEX));
        $this->indexRepository->create(self::INDEX);
        $this->assertTrue($this->indexRepository->exists(self::INDEX));

        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $this->indexRepository->delete(self::INDEX);
        echo 'index delete: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertFalse($this->indexRepository->exists(self::INDEX));
    }

    public function testIndexGetMapping()
    {
        $this->createSampleData();

        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $response = $this->indexRepository->getMapping();
        echo 'index getMapping: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertContains(self::TYPE, $response->getRawData());
    }

    public function testIndexCreateMappingTypeBased()
    {
        $this->createSampleData();

        $response = $this->indexRepository->getMapping(self::INDEX, self::TYPE);
        $mapping = $response->getData()->getGatewayValue();

        $this->indexRepository->delete(self::INDEX);
        $this->assertFalse($this->indexRepository->exists(self::INDEX));

        $this->indexRepository->create(self::INDEX);


        $timeStart = microtime(true);
        $this->indexRepository->createMapping($mapping, self::INDEX, self::TYPE);
        echo 'index createMapping: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $response = $this->indexRepository->getMapping(self::INDEX, self::TYPE);

        $this->assertEquals($mapping, $response->getData()->getGatewayValue());
    }

    private function createSampleData()
    {
        foreach($this->data as $city) {
            $this->documentRepository->create(self::INDEX, self::TYPE, $city);
        }

        $this->refreshIndex();
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


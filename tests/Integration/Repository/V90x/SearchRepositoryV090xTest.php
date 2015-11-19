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

class SearchRepositoryV090xTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @var SearchRepositoryInterface
     */
    private $searchRepository;

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
        $this->searchRepository = new SearchRepository($this->client,
            $this->serializer,
            null,
            ClientVersionMap::VERSION_V090X);

        $this->createSampleData();
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
        $this->searchRepository = null;
    }


    public function testSearchWithoutQueryParam()
    {
        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $response = $this->searchRepository->search(self::INDEX, self::TYPE);
        echo 'search: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertEquals(count($this->data), $response->getHits()['total']);
    }

    public function testSearchSizeOnly()
    {
        $size = 2;
        $query = array('size' => $size);

        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $response = $this->searchRepository->search(self::INDEX, self::TYPE, $query);
        echo 'search size only: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertEquals($size, count($response->getHitsHits()));
    }

    public function testSearchGermany()
    {
        $query = array(
            'query' => array(
                'term' => array(
                    'country' => array(
                        'value' => 'germany'
                    )
                )
            )
        );

        $timeStart = microtime(true);
        /** @var SearchResponse $response */
        $response = $this->searchRepository->search(self::INDEX, self::TYPE, $query);
        echo 'search size only: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertEquals(2, $response->getHits()['total']);

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


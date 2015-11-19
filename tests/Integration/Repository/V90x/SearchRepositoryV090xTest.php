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

class SearchRepositoryV090xTest extends AbstractElastic
{
    const TYPE = 'repository-search';


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

        $this->documentRepository = new DocumentRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V090X);
        $this->searchRepository = new SearchRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V090X);

        $this->createSampleData();
    }

    protected function tearDown()
    {
        $this->documentRepository = null;
        $this->searchRepository = null;

        parent::tearDown();
    }


    public function testSearchWithoutQueryParam()
    {
        /** @var SearchResponse $response */
        $response = $this->searchRepository->search(ES_INDEX, self::TYPE);

        $this->assertEquals(count($this->data), $response->getHits()['total']);
    }

    public function testSearchSizeOnly()
    {
        $size = 2;
        $query = array('size' => $size);

        /** @var SearchResponse $response */
        $response = $this->searchRepository->search(ES_INDEX, self::TYPE, $query);

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

        /** @var SearchResponse $response */
        $response = $this->searchRepository->search(ES_INDEX, self::TYPE, $query);

        $this->assertEquals(2, $response->getHits()['total']);
    }

    private function createSampleData()
    {
        foreach($this->data as $city) {
            $this->documentRepository->create(ES_INDEX, self::TYPE, $city);
        }

        $this->refreshIndex();
    }
}


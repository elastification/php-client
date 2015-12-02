<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 18/12/14
 * Time: 11:47
 */

namespace Elastification\Client\Tests\Integration\Repository\V2x;

use Elastification\Client\ClientVersionMap;
use Elastification\Client\Repository\DocumentRepository;
use Elastification\Client\Repository\DocumentRepositoryInterface;
use Elastification\Client\Repository\IndexRepository;
use Elastification\Client\Repository\IndexRepositoryInterface;
use Elastification\Client\Repository\SearchRepository;
use Elastification\Client\Repository\SearchRepositoryInterface;
use Elastification\Client\Response\V2x\SearchResponse;

class IndexRepositoryTest extends AbstractElastic
{
    const TYPE = 'repository-index';

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    /**
     * @var SearchRepositoryInterface
     */
    private $searchRepository;

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

        $this->documentRepository = new DocumentRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V2X);
        $this->searchRepository = new SearchRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V2X);
        $this->indexRepository = new IndexRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V2X);

    }

    protected function tearDown()
    {
        $this->documentRepository = null;
        $this->indexRepository = null;
        $this->searchRepository = null;

        parent::tearDown();
    }


    public function testIndexExistsWithoutExisting()
    {
        /** @var SearchResponse $response */
        $result = $this->indexRepository->exists(ES_INDEX);

        $this->assertFalse($result);
    }

    public function testIndexExistsWithExisting()
    {
        $this->createSampleData();

        /** @var SearchResponse $response */
        $result = $this->indexRepository->exists(ES_INDEX);

        $this->assertTrue($result);
    }

    public function testIndexCreate()
    {
        $this->assertFalse($this->indexRepository->exists(ES_INDEX));
        $this->indexRepository->create(ES_INDEX);

        $this->assertTrue($this->indexRepository->exists(ES_INDEX));
    }

    public function testIndexDelete()
    {
        $this->assertFalse($this->indexRepository->exists(ES_INDEX));
        $this->indexRepository->create(ES_INDEX);
        $this->assertTrue($this->indexRepository->exists(ES_INDEX));

        $this->indexRepository->delete(ES_INDEX);

        $this->assertFalse($this->indexRepository->exists(ES_INDEX));
    }

    public function testIndexGetMapping()
    {
        $this->createSampleData();

        /** @var SearchResponse $response */
        $response = $this->indexRepository->getMapping();

        $this->assertContains(self::TYPE, $response->getRawData());
    }

    public function testIndexCreateMappingTypeBased()
    {
        $this->createSampleData();

        $response = $this->indexRepository->getMapping(ES_INDEX, self::TYPE);
        $mapping = $response->getData()->getGatewayValue();

        $this->indexRepository->delete(ES_INDEX);
        $this->assertFalse($this->indexRepository->exists(ES_INDEX));

        $this->indexRepository->create(ES_INDEX);
        $this->indexRepository->createMapping($mapping[ES_INDEX]['mappings'], ES_INDEX, self::TYPE);

        $response = $this->indexRepository->getMapping(ES_INDEX, self::TYPE);

        $this->assertEquals($mapping, $response->getData()->getGatewayValue());
    }

    public function testGetAliasesWithoutParams()
    {
        $this->createSampleData();

        $response = $this->indexRepository->getAliases();

        $resultAsArray = $response->getData()->getGatewayValue();
        $this->assertTrue(isset($resultAsArray[ES_INDEX]));
        $this->assertEmpty($resultAsArray[ES_INDEX]['aliases']);
    }

    public function testGetAliases()
    {
        $this->createSampleData();

        $response = $this->indexRepository->getAliases(ES_INDEX);

        $resultAsArray = $response->getData()->getGatewayValue();
        $this->assertCount(1, $resultAsArray);
        $this->assertTrue(isset($resultAsArray[ES_INDEX]));
        $this->assertEmpty($resultAsArray[ES_INDEX]['aliases']);
    }

    public function testUpdateAliases()
    {
        $this->createSampleData();
        $aliasesResponse = $this->indexRepository->getAliases(ES_INDEX);
        $aliasesAsArray = $aliasesResponse->getData()->getGatewayValue();
        $this->assertCount(1, $aliasesAsArray);
        $this->assertTrue(isset($aliasesAsArray[ES_INDEX]));
        $this->assertEmpty($aliasesAsArray[ES_INDEX]['aliases']);

        $aliasPostfix = '-alias';
        $addAliases = array(
            'actions' => array(
                array(
                    'add' => array('index' => ES_INDEX, 'alias' => ES_INDEX . $aliasPostfix)
                )
            )
        );
        $removeAliases = array(
            'actions' => array(
                array(
                    'remove' => array('index' => ES_INDEX, 'alias' => ES_INDEX . $aliasPostfix)
                )
            )
        );

        $this->indexRepository->updateAliases($addAliases);

        $aliasesResponse = $this->indexRepository->getAliases(ES_INDEX);
        $aliasesAsArray = $aliasesResponse->getData()->getGatewayValue();
        $this->assertCount(1, $aliasesAsArray);
        $this->assertTrue(isset($aliasesAsArray[ES_INDEX]));
        $this->assertCount(1, $aliasesAsArray[ES_INDEX]['aliases']);

        /** @var SearchResponse $searchResult */
        $searchResult = $this->searchRepository->search(ES_INDEX . $aliasPostfix, self::TYPE);
        $this->assertSame(count($this->data), $searchResult->getHits()['total']);

        $this->indexRepository->updateAliases($removeAliases);

        $aliasesResponse = $this->indexRepository->getAliases(ES_INDEX);
        $aliasesAsArray = $aliasesResponse->getData()->getGatewayValue();
        $this->assertCount(1, $aliasesAsArray);
        $this->assertTrue(isset($aliasesAsArray[ES_INDEX]));
        $this->assertEmpty($aliasesAsArray[ES_INDEX]['aliases']);

    }

    private function createSampleData()
    {
        foreach($this->data as $city) {
            $this->documentRepository->create(ES_INDEX, self::TYPE, $city);
        }

        $this->refreshIndex();
    }
}


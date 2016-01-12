<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 18/12/14
 * Time: 11:47
 */

namespace Elastification\Client\Tests\Integration\Repository\V1x;

use Elastification\Client\ClientVersionMap;
use Elastification\Client\Repository\CatRepository;
use Elastification\Client\Repository\CatRepositoryInterface;
use Elastification\Client\Repository\DocumentRepository;
use Elastification\Client\Repository\DocumentRepositoryInterface;
use Elastification\Client\Repository\IndexRepository;
use Elastification\Client\Repository\IndexRepositoryInterface;
use Elastification\Client\Repository\SearchRepository;
use Elastification\Client\Repository\SearchRepositoryInterface;
use Elastification\Client\Response\V1x\SearchResponse;

class IndexRepositoryTest extends AbstractElastic
{
    const TYPE = 'repository-cat';

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    /**
     * @var IndexRepositoryInterface
     */
    private $indexRepository;

    /**
     * @var CatRepositoryInterface
     */
    private $catRepository;

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
            ClientVersionMap::VERSION_V1X);

        $this->indexRepository = new IndexRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V1X);

        $this->catRepository = new CatRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V1X);

    }

    protected function tearDown()
    {
        $this->documentRepository = null;
        $this->indexRepository = null;
        $this->searchRepository = null;

        parent::tearDown();
    }

    public function testAliases()
    {
        $this->createSampleData();

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

        $result = $this->catRepository->aliases();
        $data = $result->getData()->getGatewayValue();

        $this->assertCount(1, $data);
        $this->assertSame(ES_INDEX, $data[0]['index']);
        $this->assertSame(ES_INDEX . $aliasPostfix, $data[0]['alias']);

        $this->indexRepository->updateAliases($removeAliases);
    }

    public function testAllocation()
    {
        $result = $this->catRepository->allocation();
        $data = $result->getData()->getGatewayValue();

        $this->assertCount(1, $data);
        $this->assertEquals(0, $data[0]['shards']);
    }

    public function testCount()
    {
        $this->createSampleData();

        $result = $this->catRepository->count();
        $data = $result->getData()->getGatewayValue();

        $this->assertCount(1, $data);
        $this->assertEquals(4, $data[0]['count']);
    }

    public function testFieldata()
    {
        $result = $this->catRepository->fielddata();
        $data = $result->getData()->getGatewayValue();

        $this->assertCount(1, $data);
        $this->assertEquals('0b', $data[0]['total']);
    }

    public function testHealth()
    {
        $result = $this->catRepository->health();
        $data = $result->getData()->getGatewayValue();

        $this->assertCount(1, $data);
        $this->assertEquals('green', $data[0]['status']);
        $this->assertEquals(0, $data[0]['shards']);
    }

    private function createSampleData()
    {
        foreach($this->data as $city) {
            $this->documentRepository->create(ES_INDEX, self::TYPE, $city);
        }

        $this->refreshIndex();
    }
}


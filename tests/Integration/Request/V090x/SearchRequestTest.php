<?php
namespace Elastification\Client\Tests\Integration\Request\V090x;


use Elastification\Client\Request\V090x\SearchRequest;
use Elastification\Client\Response\V090x\SearchResponse;

class SearchRequestTest extends AbstractElastic
{

    const TYPE = 'request-search';

    public function testMatchAllSearch()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $searchRequest = new SearchRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $query = [
            "query" => [
                "match_all" => []
            ]
        ];

        $searchRequest->setBody($query);
        /** @var SearchResponse $response */
        $response = $this->getClient()->send($searchRequest);


        $this->assertGreaterThan(0, $response->took());
        $this->assertFalse($response->timedOut());
        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
        $this->assertGreaterThan(2, $response->totalHits());
        $this->assertGreaterThan(0, $response->maxScoreHits());

        $hits = $response->getHits();
        $this->assertArrayHasKey('total', $hits);
        $this->assertArrayHasKey('max_score', $hits);
        $this->assertArrayHasKey('hits', $hits);

        $hitsHits = $response->getHitsHits();
        foreach ($hitsHits as $hit) {
            $this->assertSame(ES_INDEX, $hit['_index']);
            $this->assertSame(self::TYPE, $hit['_type']);
        }
    }

    public function testMatchAllSearchWithParamSize()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $searchRequest = new SearchRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $query = [
            "query" => [
                "match_all" => []
            ]
        ];

        $searchRequest->setBody($query);
        $searchRequest->setParameter('size', 1);

        /** @var SearchResponse $response */
        $response = $this->getClient()->send($searchRequest);

        $this->assertGreaterThan(0, $response->took());
        $this->assertFalse($response->timedOut());
        $shards = $response->getShards();
        $this->assertTrue(isset($shards['total']));
        $this->assertTrue(isset($shards['successful']));
        $this->assertTrue(isset($shards['failed']));
        $this->assertGreaterThan(2, $response->totalHits());
        $this->assertGreaterThan(0, $response->maxScoreHits());

        $hitsHits = $response->getHitsHits();
        $this->assertCount(1, $hitsHits);
    }

    public function testMatchAllSearchWithParamScroll()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $searchRequest = new SearchRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        $query = [
            "query" => [
                "match_all" => []
            ]
        ];

        $searchRequest->setBody($query);
        $searchRequest->setParameter('scroll', '1m');
        $searchRequest->setParameter('search_type', 'scan');

        /** @var SearchResponse $response */
        $response = $this->getClient()->send($searchRequest);

        $responseData = $response->getData()->getGatewayValue();

        $this->assertTrue(isset($responseData['_scroll_id']));
        $this->assertGreaterThan(5, strlen($responseData['_scroll_id']));
    }
}

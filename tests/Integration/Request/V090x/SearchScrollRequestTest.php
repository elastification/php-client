<?php
namespace Elastification\Client\Tests\Integration\Request\V090x;


use Elastification\Client\Request\V090x\SearchRequest;
use Elastification\Client\Request\V090x\SearchScrollRequest;
use Elastification\Client\Response\V090x\SearchResponse;

class SearchScrollRequestTest extends AbstractElastic
{

    const TYPE = 'request-search-scroll';

    public function testSearchScroll()
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

        $scrollRequest = new SearchScrollRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $scrollRequest->setScroll('1m');
        $scrollRequest->setScrollId($responseData['_scroll_id']);
        /** @var SearchResponse $responseScroll */
        $responseScroll = $this->getClient()->send($scrollRequest);

        $scrollData = $responseScroll->getData()->getGatewayValue();
        $scrollHits = $responseScroll->getHitsHits();

        $this->assertTrue(isset($scrollData['_scroll_id']));
        $this->assertGreaterThan(5, strlen($scrollData['_scroll_id']));

        $this->assertCount(4, $scrollHits);
    }
}

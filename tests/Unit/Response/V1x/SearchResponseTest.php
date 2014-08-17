<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 13/08/14
 * Time: 22:39
 */

namespace Elastification\Client\Tests\Unit\Response\V1x;

use Elastification\Client\Response\V1x\SearchResponse;

class SearchResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        $this->serializer = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse('data', $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Response\ResponseInterface', $response);
        $this->assertInstanceOf('Elastification\Client\Response\Response', $response);
        $this->assertInstanceOf('Elastification\Client\Response\V1x\SearchResponse', $response);
    }

    public function testGetIndexArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_INDEX], $response->getIndex());
    }

    public function testGetIndexObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data->{SearchResponse::PROP_INDEX}, $response->getIndex());
    }

    public function testGetTypeArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_TYPE], $response->getType());
    }

    public function testGetTypeObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data->{SearchResponse::PROP_TYPE}, $response->getType());
    }

    public function testTimedOutArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_TIMED_OUT], $response->timedOut());
    }

    public function testTimedOutObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data->{SearchResponse::PROP_TIMED_OUT}, $response->timedOut());
    }

    public function testTookArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_TOOK], $response->took());
    }

    public function testTookObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data->{SearchResponse::PROP_TOOK}, $response->took());
    }

    public function testGetShardsArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_SHARDS], $response->getShards());
    }

    public function testGetShardsObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data->{SearchResponse::PROP_SHARDS}, $response->getShards());
    }

    public function testGetHitsArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_HITS], $response->getHits());
    }

    public function testGetHitsObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data->{SearchResponse::PROP_HITS}, $response->getHits());
    }

    public function testTotalHitsArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_HITS][SearchResponse::PROP_HITS_TOTAL], $response->totalHits());
    }

    public function testTotalHitsObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $hits = $data->{SearchResponse::PROP_HITS};
        $this->assertSame($hits[SearchResponse::PROP_HITS_TOTAL], $response->totalHits());
    }

    public function testTotalHitsNestedObject()
    {
        $data = $this->getData(true);
        $data->{SearchResponse::PROP_HITS} = $this->getHitsData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $hits = $data->{SearchResponse::PROP_HITS};
        $this->assertSame($hits->{SearchResponse::PROP_HITS_TOTAL}, $response->totalHits());
    }

    public function testMaxScoreHitsArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame(
            $data[SearchResponse::PROP_HITS][SearchResponse::PROP_HITS_MAX_SCORE],
            $response->maxScoreHits()
        );
    }

    public function testMaxScoreHitsObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $hits = $data->{SearchResponse::PROP_HITS};
        $this->assertSame($hits[SearchResponse::PROP_HITS_MAX_SCORE], $response->maxScoreHits());
    }

    public function testMaxScoreHitsNestedObject()
    {
        $data = $this->getData(true);
        $data->{SearchResponse::PROP_HITS} = $this->getHitsData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $hits = $data->{SearchResponse::PROP_HITS};
        $this->assertSame($hits->{SearchResponse::PROP_HITS_MAX_SCORE}, $response->maxScoreHits());
    }

    public function testHitsHitsArray()
    {
        $data = $this->getData();

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $this->assertSame($data[SearchResponse::PROP_HITS][SearchResponse::PROP_HITS_HITS], $response->getHitsHits());
    }

    public function testHitsHitsObject()
    {
        $data = $this->getData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $hits = $data->{SearchResponse::PROP_HITS};
        $this->assertSame($hits[SearchResponse::PROP_HITS_HITS], $response->getHitsHits());
    }

    public function testHitsHitsNestedObject()
    {
        $data = $this->getData(true);
        $data->{SearchResponse::PROP_HITS} = $this->getHitsData(true);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($data),
                $this->equalTo(array())
            )
            ->will($this->returnValue($data));

        /** @noinspection PhpParamsInspection */
        $response = new SearchResponse($data, $this->serializer);
        $hits = $data->{SearchResponse::PROP_HITS};
        $this->assertSame($hits->{SearchResponse::PROP_HITS_HITS}, $response->getHitsHits());
    }

    private function getData($asObject = false)
    {
        $data = [
            SearchResponse::PROP_INDEX => 'test-index',
            SearchResponse::PROP_TYPE => 'test-type',
            SearchResponse::PROP_TIMED_OUT => false,
            SearchResponse::PROP_TOOK => 1,
            SearchResponse::PROP_SHARDS => array('shard1' => array('ok')),
            SearchResponse::PROP_HITS => $this->getHitsData()
        ];

        if ($asObject) {
            return (object)$data;
        }

        return $data;
    }

    private function getHitsData($asObject = false)
    {
        $hits = array(
            SearchResponse::PROP_HITS_TOTAL => 8,
            SearchResponse::PROP_HITS_MAX_SCORE => 2,
            SearchResponse::PROP_HITS_HITS => array(
                'data' => 'test'
            )
        );

        if ($asObject) {
            return (object)$hits;
        }

        return $hits;
    }
}

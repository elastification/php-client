<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Elastification\Client\Response\Shared;

use Elastification\Client\Response\Response;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;

abstract class AbstractSearchResponse extends Response
{
    const PROP_INDEX = '_index';
    const PROP_TYPE = '_type';
    const PROP_SHARDS = '_shards';
    const PROP_TOOK = 'took';
    const PROP_TIMED_OUT = 'timed_out';
    const PROP_HITS = 'hits';
    const PROP_HITS_TOTAL = 'total';
    const PROP_HITS_MAX_SCORE = 'max_score';
    const PROP_HITS_HITS = 'hits';


    public function getIndex()
    {
        $this->processData();

        return $this->get(self::PROP_INDEX);
    }

    public function getType()
    {
        $this->processData();

        return $this->get(self::PROP_TYPE);
    }

    public function took()
    {
        $this->processData();

        return $this->get(self::PROP_TOOK);
    }

    public function timedOut()
    {
        $this->processData();

        return $this->get(self::PROP_TIMED_OUT);
    }

    public function getShards()
    {
        $this->processData();

        return $this->get(self::PROP_SHARDS);
    }

    public function getHits()
    {
        $this->processData();

        return $this->get(self::PROP_HITS);
    }

    public function totalHits()
    {
        return $this->getHitsProperty(self::PROP_HITS_TOTAL);
    }

    public function maxScoreHits()
    {
        return $this->getHitsProperty(self::PROP_HITS_MAX_SCORE);
    }

    public function getHitsHits()
    {
        return $this->getHitsProperty(self::PROP_HITS_HITS);
    }

    protected function getHitsProperty($property)
    {
        $this->processData();

        $hits = $this->get(self::PROP_HITS);

        if($hits instanceof NativeArrayGateway || is_array($hits)) {
            return $hits[$property];
        }

        return $hits->{$property};
    }
}

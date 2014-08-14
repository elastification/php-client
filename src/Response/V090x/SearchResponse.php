<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Elastification\Client\Response\V090x;

use Elastification\Client\Response\Response;

class SearchResponse extends Response
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
        $this->processData();

        $hits = $this->get(self::PROP_HITS);
        if(is_object($hits)) {
            return $hits->{self::PROP_HITS_TOTAL};
        }

        return $hits[self::PROP_HITS_TOTAL];
    }

    public function maxScoreHits()
    {
        $this->processData();

        $hits = $this->get(self::PROP_HITS);
        if(is_object($hits)) {
            return $hits->{self::PROP_HITS_MAX_SCORE};
        }

        return $hits[self::PROP_HITS_MAX_SCORE];
    }

    public function getHitsHits()
    {
        $this->processData();

        $hits = $this->get(self::PROP_HITS);
        if(is_object($hits)) {
            return $hits->{self::PROP_HITS_HITS};
        }

        return $hits[self::PROP_HITS_HITS];
    }
}

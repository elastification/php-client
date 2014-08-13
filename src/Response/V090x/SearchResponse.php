<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Dawen\Component\Elastic\Response\V090x;

use Dawen\Component\Elastic\Response\Response;

class SearchResponse extends Response
{

    public function getIndex()
    {
        $this->processData();

        return $this->data['_index'];
    }

    public function getType()
    {
        $this->processData();

        return $this->data['_type'];
    }

    public function took()
    {
        $this->processData();

        return $this->data['took'];
    }

    public function timedOut()
    {
        $this->processData();

        return $this->data['timed_out'];
    }

    public function getShards()
    {
        $this->processData();

        return $this->data['_shards'];
    }

    public function getHits()
    {
        $this->processData();

        return $this->data['hits'];
    }

    public function totalHits()
    {
        $this->processData();

        return $this->data['hits']['total'];
    }

    public function maxScoreHits()
    {
        $this->processData();

        return $this->data['hits']['max_score'];
    }

    public function getHitsHits()
    {
        $this->processData();

        return $this->data['hits']['hits'];
    }
}

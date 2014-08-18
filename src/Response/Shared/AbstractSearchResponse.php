<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace Elastification\Client\Response\Shared;

use Elastification\Client\Response\Response;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;

/**
 * Class AbstractSearchResponse
 *
 * @package Elastification\Client\Response\Shared
 * @author  Daniel Wendlandt
 */
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

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getIndex()
    {
        $this->processData();

        return $this->get(self::PROP_INDEX);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getType()
    {
        $this->processData();

        return $this->get(self::PROP_TYPE);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function took()
    {
        $this->processData();

        return $this->get(self::PROP_TOOK);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function timedOut()
    {
        $this->processData();

        return $this->get(self::PROP_TIMED_OUT);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getShards()
    {
        $this->processData();

        return $this->get(self::PROP_SHARDS);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getHits()
    {
        $this->processData();

        return $this->get(self::PROP_HITS);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function totalHits()
    {
        return $this->getHitsProperty(self::PROP_HITS_TOTAL);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function maxScoreHits()
    {
        return $this->getHitsProperty(self::PROP_HITS_MAX_SCORE);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getHitsHits()
    {
        return $this->getHitsProperty(self::PROP_HITS_HITS);
    }

    /**
     * Getter Method
     *
     * @param string $property
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    protected function getHitsProperty($property)
    {
        $this->processData();

        $hits = $this->get(self::PROP_HITS);
        return $hits[$property];
    }
}

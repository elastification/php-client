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

namespace Elastification\Client\Request\Shared;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Serializer\JmsSerializer;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class AbstractSearchScollRequest
 *
 * @package Elastification\Client\Request\Shared
 * @author  Daniel Wendlandt
 */
abstract class AbstractSearchScrollRequest extends AbstractBaseRequest
{
    const PARAM_SCROLL = 'scroll';
    const PARAM_SCROLL_ID = 'scroll_id';
    const REQUEST_ACTION = '_search/scroll';

    public function __construct($index, $type, SerializerInterface $serializer, array $serializerParams = array())
    {
        //overwriting index type for this request
        parent::__construct(null, null, $serializer, $serializerParams);
    }

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::GET;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return self::REQUEST_ACTION;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        //do nothing
    }

    /**
     * Sets the scroll_id as query parameter for request
     *
     * @param string $scrollId
     * @author Daniel Wendlandt
     */
    public function setScrollId($scrollId)
    {
        $this->setParameter(self::PARAM_SCROLL_ID, $scrollId);
    }

    /**
     * Sets the scroll parameter as query parameter.
     * Parameter is in elastichsearch time unit
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#time-units
     *
     * @param $scrollTimeUnit
     * @author Daniel Wendlandt
     */
    public function setScroll($scrollTimeUnit)
    {
        $this->setParameter(self::PARAM_SCROLL, $scrollTimeUnit);
    }

}

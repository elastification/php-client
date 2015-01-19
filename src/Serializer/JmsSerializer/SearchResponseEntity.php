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
namespace Elastification\Client\Serializer\JmsSerializer;

use JMS\Serializer\Annotation as JMS;

/**
 * Response Entity for Elasticseach Responses.
 * @package Elastification\Client\Serializer\JmsSerializer
 * @author Mario Mueller
 */
class SearchResponseEntity
{
    /**
     * @JMS\Type("integer")
     * @var integer
     */
    public $took;

    /**
     * @JMS\Type("boolean")
     * @var boolean
     */
    public $timed_out;

    /**
     * @JMS\SerializedName("_shards")
     * @JMS\Type("Elastification\Client\Serializer\JmsSerializer\Shards")
     * @var \Elastification\Client\Serializer\JmsSerializer\Shards
     */
    public $_shards;

    /**
     * @JMS\Type("Elastification\Client\Serializer\JmsSerializer\Hits")
     * @var \Elastification\Client\Serializer\JmsSerializer\Hits
     */
    public $hits;

    /**
     * @JMS\Type("array")
     * @var array
     */
    public $aggregations;

    /**
     * @JMS\Type("array")
     * @var array
     */
    public $facets;
}

<?php
namespace Elastification\Client\Serializer\JmsSerializer;

use JMS\Serializer\Annotation as JMS;

/**
 * Response Entity for Elasticseach Responses.
 * @package Elastification\Client\Serializer\JmsSerializer
 * @author Mario Mueller
 * @since 2014-08-15
 * @version 1.0.0
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
     * @JMS\Type("stdClass")
     * @var \stdClass
     */
    public $aggregations;

    /**
     * @JMS\Type("stdClass")
     * @var \stdClass
     */
    public $factes;
}

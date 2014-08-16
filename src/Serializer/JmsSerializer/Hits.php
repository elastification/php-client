<?php
namespace Elastification\Client\Serializer\JmsSerializer;

use JMS\Serializer\Annotation as JMS;

/**
 * @package Elastification\Client\Serializer\JmsSerializer
 * @author Mario Mueller <mueller@freshcells.de>
 * @since 2014-08-15
 * @version 1.0.0
 */
class Hits
{
    /**
     * @JMS\Type("integer")
     * @var integer
     */
    public $total;

    /**
     * @JMS\Type("double")
     * @JMS\SerializedName("max_score")
     * @var double
     */
    public $maxScore;

    /**
     * @JMS\Type("array<Elastification\Client\Serializer\JmsSerializer\Hit>")
     * @var \Elastification\Client\Serializer\JmsSerializer\Hit[]
     */
    public $hits = [];
}

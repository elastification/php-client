<?php
namespace Elastification\Client\Serializer\JmsSerializer;

use JMS\Serializer\Annotation as JMS;

/**
 * @package Elastification\Client\Serializer\JmsSerializer
 * @author Mario Mueller
 * @since 2014-08-15
 * @version 1.0.0
 */
class Hit
{
    /**
     * @JMS\Type("string")
     * @var string
     */
    public $_index;

    /**
     * @JMS\Type("string")
     * @var string
     */
    public $_type;

    /**
     * @JMS\Type("string")
     * @var string
     */
    public $_id;

    /**
     * @JMS\Type("double")
     * @var double
     */
    public $_score;

    /**
     * @JMS\Type("ElastificationSource")
     * @var object
     */
    public $_source;
}

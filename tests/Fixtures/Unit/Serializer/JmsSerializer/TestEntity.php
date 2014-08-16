<?php
namespace Elastification\Client\Tests\Fixtures\Unit\Serializer\JmsSerializer;

use JMS\Serializer\Annotation\Type;

/**
 * @package Elastification\Client\Tests\Unit\Serializer
 * @author Mario Mueller
 * @since 2014-08-16
 * @version 1.0.0
 */
class TestEntity
{
    /**
     * @Type("integer")
     * @var int
     */
    public $a;
}

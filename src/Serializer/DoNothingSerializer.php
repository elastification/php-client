<?php
namespace Elastification\Client\Serializer;

/**
 * A simple pass-through "serializer".
 *
 * @package Elastification\Client\Serializer
 * @author  Daniel Wendlandt
 */
class DoNothingSerializer implements SerializerInterface
{

    /**
     * @inheritdoc
     */
    public function serialize($data, array $params = array())
    {
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function deserialize($data, array $params = array())
    {
        return $data;
    }
}

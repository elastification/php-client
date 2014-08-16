<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 18:38
 */

namespace Elastification\Client\Serializer;

/**
 * Class DoNothingSerializer
 * @package Elastification\Client\Serializer
 * @author Daniel Wendlandt
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
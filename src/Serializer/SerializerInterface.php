<?php

namespace Elastification\Client\Serializer;

use Elastification\Client\Serializer\Gateway\GatewayInterface;

/**
 * Interface SerializerInterface
 * @package Elastification\Client\Serializer
 * @author Daniel Wendlandt
 */
interface SerializerInterface
{
    /**
     * Serializes given data to string
     *
     * @param mixed $data
     * @param array $params
     * @return string
     * @author Daniel Wendlandt
     */
    public function serialize($data, array $params = array());

    /**
     * Deserializes given data to array or object
     *
     * @param string $data
     * @param array $params
     * @return GatewayInterface
     * @author Daniel Wendlandt
     */
    public function deserialize($data, array $params = array());
}

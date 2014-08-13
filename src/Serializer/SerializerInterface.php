<?php

namespace Dawen\Component\Elastic\Serializer;

use Dawen\Component\Elastic\Serializer\Gateway\GatewayInterface;

interface SerializerInterface
{
    /**
     * Serializes given data to string
     *
     * @param mixed $data
     * @param array $params
     * @return string
     */
    public function serialize($data, array $params = array());

    /**
     * Deserializes given data to array or object
     *
     * @param string $data
     * @param array $params
     * @return GatewayInterface
     */
    public function deserialize($data, array $params = array());
}

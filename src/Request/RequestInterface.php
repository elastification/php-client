<?php

namespace Dawen\Component\Elastic\Request;

use Dawen\Component\Elastic\Serializer\SerializerInterface;

interface RequestInterface
{
    /**
     * Gets the elasticsearch index
     *
     * @return null|string
     */
    public function getIndex();

    /**
     * Gets the elasticsearch type
     *
     * @return null|string
     */
    public function getType();

    /**
     * Gets the request method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Gets the elasticsearch action/endpoint like
     * (_search, _mapping)
     *
     * @return null|string
     */
    public function getAction();

    /**
     * @return SerializerInterface
     */
    public function getSerializer();

    /**
     * get the body
     *
     * @return mixed
     */
    public function getBody();

    /**
     * before setting data it should be serialized
     *
     * @param mixed $body
     * @throws \Dawen\Component\Elastic\Exception\RequestException
     */
    public function setBody($body);

    /**
     * @param $version
     * @param string $rawData
     * @param \Dawen\Component\Elastic\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return mixed
     */
    public function createResponse(
        $version,
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array());

}
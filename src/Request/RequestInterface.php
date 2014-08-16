<?php

namespace Elastification\Client\Request;

use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Serializer\SerializerInterface;

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
     * @return array
     */
    public function getSerializerParams();

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
     * @return void
     * @throws \Elastification\Client\Exception\RequestException
     */
    public function setBody($body);

    /**
     * @param string $rawData
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return null|ResponseInterface
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array());

    /**
     * gets a response class name that is supported by this class
     *
     * @return string
     */
    public function getSupportedClass();

}

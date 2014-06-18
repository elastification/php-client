<?php

namespace Dawen\Component\Elastic\Request;

use Dawen\Component\Elastic\Serializer\SerializerInterface;

interface RequestInterface
{
    /**
     * Gets the elasticsearch index
     *
     * @return string
     */
    public function getIndex();

    /**
     * Gets the elasticsearch type
     *
     * @return string
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
     * @return string
     */
    public function getAction();

    /**
     * @return SerializerInterface
     */
    public function getSerializer();
}
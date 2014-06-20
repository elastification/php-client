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

}
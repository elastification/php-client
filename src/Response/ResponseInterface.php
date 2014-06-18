<?php

namespace Dawen\Component\Elastic\Response;

use Dawen\Component\Elastic\Serializer\SerializerInterface;

interface ResponseInterface
{
    /**
     * Gets the converted data of the response
     *
     * @return mixed
     */
    public function getData();

    /**
     * Gets the raw not converted data of the response
     *
     * @return string
     */
    public function getRawData();

    /**
     * Gets the current defined serializer
     *
     * @return SerializerInterface
     */
    public function getSerializer();
}
<?php

namespace Elastification\Client\Response;

use Elastification\Client\Serializer\SerializerInterface;

interface ResponseInterface
{
    /**
     * @param string $rawData
     * @param SerializerInterface $serializer
     * @param array $serializerParams
     */
    public function __construct($rawData, SerializerInterface $serializer, array $serializerParams = array());

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
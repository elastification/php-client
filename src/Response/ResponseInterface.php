<?php

namespace Elastification\Client\Response;

use Elastification\Client\Serializer\SerializerInterface;

/**
 * Interface ResponseInterface
 * @package Elastification\Client\Response
 * @author Daniel Wendlandt
 */
interface ResponseInterface
{
    /**
     * @param string $rawData
     * @param SerializerInterface $serializer
     * @param array $serializerParams
     * @author Daniel Wendlandt
     */
    public function __construct($rawData, SerializerInterface $serializer, array $serializerParams = array());

    /**
     * Gets the converted data of the response
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getData();

    /**
     * Gets the raw not converted data of the response
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getRawData();

    /**
     * Gets the current defined serializer
     *
     * @return SerializerInterface
     * @author Daniel Wendlandt
     */
    public function getSerializer();
}

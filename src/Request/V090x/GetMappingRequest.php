<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:09
 */

namespace Elastification\Client\Request\V090x;

use Elastification\Client\Request\Shared\AbstractGetMappingRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Serializer\SerializerInterface;

class GetMappingRequest extends AbstractGetMappingRequest
{
    /**
     * @param string $rawData
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return ResponseInterface
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array())
    {
        return new Response($rawData, $serializer, $serializerParams);
    }

    /**
     * gets a response class name that is supported by this class
     *
     * @return string
     */
    public function getSupportedClass()
    {
        return 'Elastification\Client\Response\Response';
    }

}
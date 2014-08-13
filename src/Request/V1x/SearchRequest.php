<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:09
 */

namespace Elastification\Client\Request\V1x;

use Elastification\Client\Request\Shared\AbstractSearchRequest;
use Elastification\Client\Response\V1x\DocumentResponse;
use Elastification\Client\Response\V1x\SearchResponse;
use Elastification\Client\Serializer\SerializerInterface;

class SearchRequest extends AbstractSearchRequest
{
    /**
     * @param string $rawData
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return DocumentResponse
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array())
    {
        return new SearchResponse($rawData, $serializer, $serializerParams);
    }

    /**
     * gets a response class name that is supported by this class
     *
     * @return string
     */
    public function getSupportedClass()
    {
        return 'Elastification\Client\Response\V1x\SearchResponse';
    }
}

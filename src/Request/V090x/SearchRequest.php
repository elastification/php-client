<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:09
 */

namespace Dawen\Component\Elastic\Request\V090x;

use Dawen\Component\Elastic\Request\Shared\AbstractSearchRequest;
use Dawen\Component\Elastic\Response\V090x\DocumentResponse;
use Dawen\Component\Elastic\Response\V090x\SearchResponse;
use Dawen\Component\Elastic\Serializer\SerializerInterface;

class SearchRequest extends AbstractSearchRequest
{
    /**
     * @param string $rawData
     * @param \Dawen\Component\Elastic\Serializer\SerializerInterface $serializer
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
        return 'Dawen\Component\Elastic\Response\V090x\SearchResponse';
    }
}
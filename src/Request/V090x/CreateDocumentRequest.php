<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Elastification\Client\Request\V090x;

use Elastification\Client\Request\Shared\AbstractCreateDocumentRequest;
use Elastification\Client\Response\V090x\CreateUpdateDocumentResponse;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class CreateDocumentRequest
 * @package Elastification\Client\Request\V090x
 * @author Daniel Wendlandt
 */
class CreateDocumentRequest extends AbstractCreateDocumentRequest
{

    /**
     * @param string $rawData
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return CreateUpdateDocumentResponse
     * @author Daniel Wendlandt
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array())
    {
        return new CreateUpdateDocumentResponse($rawData, $serializer, $serializerParams);
    }

    /**
     * gets a response class name that is supported by this class
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getSupportedClass()
    {
        return 'Elastification\Client\Response\V090x\CreateUpdateDocumentResponse';
    }
}
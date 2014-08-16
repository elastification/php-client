<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:09
 */

namespace Elastification\Client\Request\V090x;

use Elastification\Client\Request\Shared\AbstractDeleteDocumentRequest;
use Elastification\Client\Response\V090x\DeleteDocumentResponse;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class DeleteDocumentRequest
 * @package Elastification\Client\Request\V090x
 * @author Daniel Wendlandt
 */
class DeleteDocumentRequest extends AbstractDeleteDocumentRequest
{
    /**
     * @param string $rawData
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return DeleteDocumentResponse
     * @author Daniel Wendlandt
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array())
    {
        return new DeleteDocumentResponse($rawData, $serializer, $serializerParams);
    }

    /**
     * gets a response class name that is supported by this class
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getSupportedClass()
    {
        return 'Elastification\Client\Response\V090x\DeleteDocumentResponse';
    }
}
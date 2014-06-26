<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:09
 */

namespace Dawen\Component\Elastic\Request\V090x;

use Dawen\Component\Elastic\Request\Shared\AbstractGetDocumentRequest;
use Dawen\Component\Elastic\Request\Shared\AbstractUpdateDocumentRequest;
use Dawen\Component\Elastic\Response\ResponseInterface;
use Dawen\Component\Elastic\Response\V090x\CreateUpdateDocumentResponse;
use Dawen\Component\Elastic\Serializer\SerializerInterface;

class UpdateDocumentRequest extends AbstractUpdateDocumentRequest
{
    /**
     * @param string $rawData
     * @param \Dawen\Component\Elastic\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return CreateUpdateDocumentResponse
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
     */
    public function getSupportedClass()
    {
        return 'Dawen\Component\Elastic\Response\V090x\CreateUpdateDocumentResponse';
    }
}
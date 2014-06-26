<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:09
 */

namespace Dawen\Component\Elastic\Request\V090x;

use Dawen\Component\Elastic\Request\Shared\AbstractDeleteDocumentRequest;
use Dawen\Component\Elastic\Response\Response;
use Dawen\Component\Elastic\Response\ResponseInterface;
use Dawen\Component\Elastic\Serializer\SerializerInterface;

class DeleteDocumentRequest extends AbstractDeleteDocumentRequest
{
    /**
     * @param string $rawData
     * @param \Dawen\Component\Elastic\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     * @return null|ResponseInterface
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
        return 'Dawen\Component\Elastic\Response\Response';
    }
}
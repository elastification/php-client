<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Dawen\Component\Elastic\Request\Shared;

use Dawen\Component\Elastic\Request\RequestInterface;
use Dawen\Component\Elastic\Request\RequestMethods;
use Dawen\Component\Elastic\Serializer\SerializerInterface;

class CreateDocumentRequest implements RequestInterface
{
    /**
     * @var \Dawen\Component\Elastic\Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @var null|string
     */
    private $index = null;

    /**
     * @var null|string
     */
    private $type = null;

    private $body = null;

    /**
     * @param \Dawen\Component\Elastic\Serializer\SerializerInterface $serialzer
     * @param string $index
     * @param string $type
     */
    public function __construct(SerializerInterface $serialzer, $index, $type)
    {
        $this->serializer = $serialzer;

        if(!empty($index)) {
            $this->index = $index;
        }

        if(!empty($type)) {
            $this->type = $type;
        }
    }

    /**
     * Gets the elasticsearch index
     *
     * @return null|string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Gets the elasticsearch type
     *
     * @return null|string
     */
    public function getType()
    {
        return $this->getType();
    }

    /**
     * Gets the request method
     *
     * @return string
     */
    public function getMethod()
    {
        return RequestMethods::POST;
    }

    /**
     * Gets the elasticsearch action/endpoint like
     * (_search, _mapping)
     *
     * @return null|string
     */
    public function getAction()
    {
        return null;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }



    /**
     * @return null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }


}
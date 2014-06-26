<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Dawen\Component\Elastic\Request\Shared;

use Dawen\Component\Elastic\Exception\RequestException;
use Dawen\Component\Elastic\Request\RequestInterface;
use Dawen\Component\Elastic\Request\RequestMethods;
use Dawen\Component\Elastic\Serializer\SerializerInterface;

abstract class AbstractUpdateDocumentRequest implements RequestInterface
{
    /**
     * @var \Dawen\Component\Elastic\Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     */
    private $serializerParams = array();

    /**
     * @var null|string
     */
    private $index = null;

    /**
     * @var null|string
     */
    private $type = null;

    /**
     * @var null|string
     */
    private $body = null;

    /**
     * @var null|string
     */
    private $id = null;

    /**
     * @param string $index
     * @param string $type
     * @param \Dawen\Component\Elastic\Serializer\SerializerInterface $serializer
     * @param array $serializerParams
     */
    public function __construct($index, $type, SerializerInterface $serializer, array $serializerParams = array())
    {
        $this->serializer = $serializer;
        $this->serializerParams = $serializerParams;

        if(!empty($index)) {
            $this->index = $index;
        }

        if(!empty($type)) {
            $this->type = $type;
        }
    }

    /**
     * @inheritdoc
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::PUT;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        if(null === $this->id) {
            throw new RequestException('id can not be empty for this request');
        }

        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @inheritdoc
     */
    public function getSerializerParams()
    {
        return $this->serializerParams;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        if(empty($body)) {
            throw new RequestException('Body can not be empty');
        }

        $this->body = $this->serializer->serialize($body, $this->serializerParams);
    }

    /**
     * Sets the document id
     *
     * @param null|string $id
     * @throws \Dawen\Component\Elastic\Exception\RequestException
     */
    public function setId($id)
    {
        if(empty($id)) {
            throw new RequestException('Id can not be empty');
        }

        $this->id = $id;
    }


}
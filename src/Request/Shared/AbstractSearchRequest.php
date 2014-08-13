<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:09
 */

namespace Elastification\Client\Request\Shared;

use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Serializer\SerializerInterface;

abstract class AbstractSearchRequest implements RequestInterface
{
    const REQUEST_ACTION = '_search';

    /**
     * @var \Elastification\Client\Serializer\SerializerInterface
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
     * @param string $index
     * @param string $type
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
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
        return RequestMethods::POST;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return self::REQUEST_ACTION;
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
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        $this->body = $this->getSerializer()->serialize($body, $this->serializerParams);
    }

    /**
     * @inheritdoc
     */
    public function getSerializerParams()
    {
        return $this->serializerParams;
    }
}
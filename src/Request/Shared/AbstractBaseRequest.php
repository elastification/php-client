<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Elastification\Client\Request\Shared;

use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Serializer\SerializerInterface;

abstract class AbstractBaseRequest implements RequestInterface
{
    /**
     * @var \Elastification\Client\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $serializerParams = array();

    /**
     * @var null|string
     */
    protected $index = null;

    /**
     * @var null|string
     */
    protected $type = null;

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

}
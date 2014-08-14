<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Elastification\Client\Response;

use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class Response
 * @package Elastification\Client\Response
 * @author Daniel Wendlandt
 */
class Response implements ResponseInterface
{

    /**
     * @var string
     */
    private $rawData;

    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     */
    private $serializerParams;

    /**
     * @param $rawData
     * @param SerializerInterface $serializer
     * @param array $serializerParams
     */
    public function __construct($rawData, SerializerInterface $serializer, array $serializerParams = array())
    {
        $this->rawData = $rawData;
        $this->serializer = $serializer;
        $this->serializerParams = $serializerParams;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $this->processData();

        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @inheritdoc
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * checks if data is already deserialized.
     * @author Daniel Wendlandt
     */
    protected function processData()
    {
        if(null === $this->data) {
            if(null !== $this->rawData) {
                $this->data = $this->serializer->deserialize($this->rawData, $this->serializerParams);
            }
        }
    }

    /**
     * gets a property of the data object/array
     *
     * @param string $property
     * @return mixed
     * @author Daniel Wendlandt
     */
    protected function get($property) {
        if(is_object($this->data)) {
            return $this->data->{$property};
        }

        return $this->data[$property];
    }

    /**
     * checks if a property of data array/object exists
     *
     * @param string $property
     * @return bool
     * @author Daniel Wendlandt
     */
    protected function has($property) {
        if(is_object($this->data)) {
            return property_exists($this->data, $property);
        }

        return isset($this->data[$property]);
    }
}

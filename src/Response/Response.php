<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Elastification\Client\Response;

use Elastification\Client\Serializer\SerializerInterface;

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
     */
    protected function processData()
    {
        if(null === $this->data) {
            $this->data = $this->serializer->deserialize($this->rawData, $this->serializerParams);
        }
    }
}

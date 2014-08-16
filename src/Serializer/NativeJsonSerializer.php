<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 18:38
 */

namespace Elastification\Client\Serializer;

use Elastification\Client\Serializer\Exception\DeserializationFailureException;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

class NativeJsonSerializer implements SerializerInterface
{

    /**
     * @inheritdoc
     */
    public function serialize($data, array $params = array())
    {
        return json_encode($data);
    }

    /**
     * @inheritdoc
     */
    public function deserialize($data, array $params = array())
    {
        $assoc = true;
        if(isset($params['assoc']) && is_bool($params['assoc'])) {
            $assoc = $params['assoc'];
        }

        $decodedJson = json_decode($data, $assoc);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $this->createGateway($assoc, $decodedJson);
        }

        // json_last_error_msg is only present in 5.5 and higher.
        if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            throw new DeserializationFailureException(json_last_error_msg(), json_last_error());
        }
        else {
            throw new DeserializationFailureException('JSON syntax error in: ' . $data);
        }
    }

    /**
     * @author Mario Mueller
     * @param boolean $assoc
     * @param mixed $decodedJson
     * @return \Elastification\Client\Serializer\Gateway\GatewayInterface
     */
    private function createGateway($assoc, $decodedJson)
    {
        if ($assoc === true) {
            $instance = new NativeArrayGateway($decodedJson);
        } else {
            $instance = new NativeObjectGateway($decodedJson);
        }
        return $instance;
    }
}

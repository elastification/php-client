<?php
namespace Elastification\Client\Serializer;

use Elastification\Client\Serializer\Exception\DeserializationFailureException;
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

/**
 * Simple Native JSON Serializer.
 *
 * Serializes the response using the native json_encode/json_decode commands. the params allow you to control
 * the type of result, either a stdClass or an array/hash map.
 *
 * @package Elastification\Client\Serializer
 * @author  Daniel Wendlandt
 */
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
        $assoc = $this->useAssoc($params);
        $decodedJson = json_decode($data, $assoc);

        if ($decodedJson != null) {
            return $this->createGateway($assoc, $decodedJson);
        }

        // json_last_error_msg is only present in 5.5 and higher.
        if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            throw new DeserializationFailureException(json_last_error_msg(), json_last_error());
        }

        // Fall through for versions below 5.5
        throw new DeserializationFailureException('JSON syntax error in: ' . $data);
    }

    /**
     * Decides whether to use assoc or stdClass.
     *
     * @param array $params The array of params.
     *
     * @return boolean
     * @author Mario Mueller
     */
    private function useAssoc($params)
    {
        $assoc = true;
        if (isset($params['assoc']) && is_bool($params['assoc'])) {
            $assoc = $params['assoc'];
        }
        return $assoc;
    }

    /**
     * Creates the correct gateway based on assoc being true or false.
     *
     * The information if we need assoc could also be determined by looking
     * at the type of $decodedJson, but we thing a boolean is faster and the
     * decision has already been made before.
     *
     * @author Mario Mueller
     *
     * @param boolean         $assoc Should we use assoc?
     * @param array|\stdClass $decodedJson
     *
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

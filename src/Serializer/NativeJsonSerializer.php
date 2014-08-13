<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 18:38
 */

namespace Dawen\Component\Elastic\Serializer;

use Dawen\Component\Elastic\Serializer\Exception\DeserializationFailureException;
use Dawen\Component\Elastic\Serializer\Gateway\NativeArrayGateway;
use Dawen\Component\Elastic\Serializer\Gateway\NativeObjectGateway;

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
            if ($assoc) {
                return new NativeArrayGateway($decodedJson);
            } else {
                return new NativeObjectGateway($decodedJson);
            }

        } else {
            throw new DeserializationFailureException(json_last_error_msg(), json_last_error());
        }
    }
}

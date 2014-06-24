<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 18:38
 */

namespace Dawen\Component\Elastic\Serializer;

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
        return json_decode($data, $assoc);
    }
}
<?php
namespace Elastification\Client\Serializer\Gateway;

/**
 * @package Elastification\Client\Serializer\Gateway
 * @author  Mario Mueller
 */
abstract class GatewayFactory
{
    /**
     * @param mixed $value
     *
     * @return GatewayInterface|mixed
     * @author Mario Mueller
     */
    public static function factory($value)
    {
        if (is_array($value)) {
            return new NativeArrayGateway($value);
        } elseif (is_object($value)) {
            return new NativeObjectGateway($value);
        }
        return $value;
    }
}

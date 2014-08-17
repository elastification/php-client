<?php
namespace Elastification\Client\Serializer\Gateway;
/**
 * Interface for accessing properties of the serialized data.
 *
 * The idea behind the gateway is to create a simple abstraction for the response
 * layer to access things like the response time ("took") and to get to know if the request went well, etc.
 *
 * The gateway is not meant to be used outside of the ecosystem of the client. Use the
 * {@see Elastification\Client\Serializer\Gateway\GatewayInterface#getGatewayValue} method to get the real, un-managed
 * result of your chosen serializer.
 *
 * For performance reasons you should call this method as soon as possible in your code and work with the real result.
 *
 * @author Mario Mueller
 */
interface GatewayInterface extends \ArrayAccess, \Iterator, \Countable
{
    /**
     * Returns the original value.
     *
     * @return mixed
     * @author Mario Mueller
     */
    public function getGatewayValue();
}

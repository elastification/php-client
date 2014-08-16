<?php
namespace Elastification\Client\Serializer\Gateway;
/**
 * Interface for accessing properties of the serialized data.
 *
 * Should only be used internally.
 *
 * @author Mario Mueller
 */
interface GatewayInterface extends \ArrayAccess
{
    /**
     * Returns the original value.
     *
     * @return mixed
     * @author Mario Mueller
     */
    public function getGatewayValue();
}

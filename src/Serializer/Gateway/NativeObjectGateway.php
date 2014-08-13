<?php
namespace Elastification\Client\Serializer\Gateway;

/**
 * @package Elastification\Client\Serializer\Gateway
 * @author Mario Mueller <mueller@freshcells.de>
 * @since 2014-08-13
 * @version 1.0.0
 */
class NativeObjectGateway implements GatewayInterface
{
    /**
     * @var object
     */
    private $jsonData;

    /**
     * @param object $jsonData
     */
    function __construct($jsonData)
    {
        $this->jsonData = $jsonData;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->jsonData->{$offset});
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        $value = $this->jsonData->$offset;
        if (is_scalar($value)) {
            return $value;
        } else {
            return new NativeObjectGateway($this->jsonData->$offset);
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('The result set is immutable');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('The result set is immutable');
    }
}

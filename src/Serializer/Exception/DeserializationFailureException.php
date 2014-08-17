<?php
namespace Elastification\Client\Serializer\Exception;

/**
 * Part of the exception translator pattern.
 *
 * Since PHP 5.3 you can pass a "previous exception" to any exception that inherits from \Exception.
 * If you implement you own serialization layer, please use this exception to translate the raw
 * exception of the serialization into a DeserializationFailureException.
 *
 * @package Elastification\Client\Serializer\Exception
 * @author  Mario Mueller
 */
class DeserializationFailureException extends \RuntimeException
{
}

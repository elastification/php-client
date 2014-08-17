<?php
namespace Elastification\Client\Transport\Exception;

/**
 * Part of the exception translator pattern.
 *
 * Since PHP 5.3 you can pass a "previous exception" to any exception that inherits from \Exception.
 * If you implement you own transport layer, please use this exception to translate the raw
 * exception of the transport into a TransportLayerException.
 *
 * @package Elastification\Client\Transport\Exception
 * @author  Mario Mueller
 */
class TransportLayerException extends \RuntimeException
{
}

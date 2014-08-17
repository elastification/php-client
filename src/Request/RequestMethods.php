<?php

namespace Elastification\Client\Request;

/**
 * Class RequestMethods
 * @package Elastification\Client\Reuqest
 * @author Daniel Wendlandt
 */
final class RequestMethods
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * @var array
     */
    private static $allowed = array(
        self::GET,
        self::POST,
        self::PUT,
        self::DELETE
    );

    /**
     * Checks if the given method is allowed
     *
     * @param string $method
     * @return bool
     * @author Daniel Wendlandt
     */
    public static function isAllowed($method)
    {
        return in_array($method, self::$allowed);
    }
}

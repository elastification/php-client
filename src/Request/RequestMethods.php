<?php

namespace Dawen\Component\Elastic\Request;

/**
 * Class RequestMethods
 * @package Dawen\Component\Elastic\Reuqest
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
    public static $allowed = array(
        self::GET,
        self::POST,
        self::PUT,
        self::DELETE
    );
}

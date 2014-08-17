<?php
namespace Elastification\Client\Transport;

/**
 * Represents a transport response.
 *
 * @package Elastification\Client\Transport
 * @author  Mario Mueller
 */
interface TransportResponseInterface
{
    /**
     * @return string The raw response string.
     * @author Mario Mueller
     */
    public function getBody();
}

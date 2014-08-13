<?php
namespace Elastification\Client\Transport;

/**
 * Interface TransportResponseInterface
 * @package Elastification\Client\Transport
 * @author Mario Mueller
 */
interface TransportResponseInterface
{
    /**
     * @return string The raw response string.
     * @author Mario Mueller
     */
    public function getBody();
}

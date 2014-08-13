<?php
namespace Dawen\Component\Elastic\Transport;

/**
 * Interface TransportResponseInterface
 * @package Dawen\Component\Elastic\Transport
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

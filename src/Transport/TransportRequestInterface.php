<?php
namespace Elastification\Client\Transport;

/**
 * Represents a transport request.
 *
 * @package Elastification\Client\Transport
 * @author  Mario Mueller
 */
interface TransportRequestInterface
{
    /**
     * @param string $body The raw request body.
     * @return void
     * @author Mario Mueller
     */
    public function setBody($body);

    /**
     * @param string $path The path according to the Elasticsearch http interface.
     * @return void
     * @author Mario Mueller
     */
    public function setPath($path);

    /**
     * @param array $params
     * @return void
     * @author Mario Mueller
     */
    public function setQueryParams(array $params);
}

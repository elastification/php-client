<?php
namespace Elastification\Client\Transport;

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
}

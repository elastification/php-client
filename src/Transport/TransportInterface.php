<?php
namespace Elastification\Client\Transport;

/**
 * Interface TransportInterface
 * @author Mario Mueller
 */
interface TransportInterface
{
    /**
     * @param string $httpMethod The http method to use.
     * @return \Elastification\Client\Transport\TransportRequestInterface
     * @author Mario Mueller
     */
    public function createRequest($httpMethod);

    /**
     * @param TransportRequestInterface $request The configured request to send.
     * @return \Elastification\Client\Transport\TransportResponseInterface
     * @author Mario Mueller
     */
    public function send(TransportRequestInterface $request);
}

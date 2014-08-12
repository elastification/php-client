<?php
namespace Dawen\Component\Elastic\Transport;

/**
 * Interface TransportInterface
 * @author Mario Mueller
 */
interface TransportInterface
{
    /**
     * @param string $httpMethod The http method to use.
     * @return \Dawen\Component\Elastic\Transport\TransportRequestInterface
     * @author Mario Mueller
     */
    public function createRequest($httpMethod);

    /**
     * @param TransportRequestInterface $request The configured request to send.
     * @return \Dawen\Component\Elastic\Transport\TransportResponseInterface
     * @author Mario Mueller
     */
    public function send(TransportRequestInterface $request);
}

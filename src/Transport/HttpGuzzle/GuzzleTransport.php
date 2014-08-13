<?php
namespace Elastification\Client\Transport\HttpGuzzle;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Transport\Exception\TransportLayerException;
use Elastification\Client\Transport\TransportInterface;
use Elastification\Client\Transport\TransportRequestInterface;
use GuzzleHttp\ClientInterface;

/**
 * @package Elastification\Client\Transport\HttpGuzzle
 * @author Mario Mueller
 * @since 2014-08-12
 * @version 1.0.0
 */
class GuzzleTransport implements TransportInterface
{
    /**
     * @var ClientInterface
     */
    private $guzzleClient;

    /**
     * @param ClientInterface $guzzleClient
     */
    public function __construct(ClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param string $httpMethod The http method to use.
     * @return TransportRequestInterface
     * @author Mario Mueller
     */
    public function createRequest($httpMethod)
    {
        return new GuzzleTransportRequest($this->guzzleClient->createRequest($httpMethod));
    }

    /**
     * @param TransportRequestInterface $request The configured request to send.
     * @throws \Elastification\Client\Exception\ClientException
     * @return \Elastification\Client\Transport\TransportResponseInterface
     * @author Mario Mueller
     */
    public function send(TransportRequestInterface $request)
    {
        try {
            return new GuzzleTransportResponse($this->guzzleClient->send($request->getWrappedRequest()));
        } catch (\Exception $exception) {
            throw new TransportLayerException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}

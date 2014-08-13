<?php
namespace Dawen\Component\Elastic\Transport\HttpGuzzle;

use Dawen\Component\Elastic\Exception\ClientException;
use Dawen\Component\Elastic\Transport\Exception\TransportLayerException;
use Dawen\Component\Elastic\Transport\TransportInterface;
use Dawen\Component\Elastic\Transport\TransportRequestInterface;
use GuzzleHttp\ClientInterface;

/**
 * @package Dawen\Component\Elastic\Transport\HttpGuzzle
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
     * @throws \Dawen\Component\Elastic\Exception\ClientException
     * @return \Dawen\Component\Elastic\Transport\TransportResponseInterface
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

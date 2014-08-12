<?php
namespace Dawen\Component\Elastic\Transport\HttpGuzzle;

use Dawen\Component\Elastic\Exception\ClientException;
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
        $guzzleRequest = $this->guzzleClient->createRequest($httpMethod);
        $transportRequest = new GuzzleTransportRequest($guzzleRequest);
        return $transportRequest;
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
            // XXX: Not nice, but otherwise we break the interface. Any better idea?
            $wrappedRequest = $request->getWrappedRequest();
            $response = $this->guzzleClient->send($wrappedRequest);
            $transportResponse = new GuzzleTransportResponse($response);
            return $transportResponse;
        } catch (\Exception $exception) {
            throw new ClientException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}

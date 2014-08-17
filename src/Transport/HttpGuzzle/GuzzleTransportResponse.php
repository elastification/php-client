<?php
namespace Elastification\Client\Transport\HttpGuzzle;

use Elastification\Client\Transport\TransportResponseInterface;
use GuzzleHttp\Message\ResponseInterface;

/**
 * @package Elastification\Client\Transport\HttpGuzzle
 * @author  Mario Mueller
 */
class GuzzleTransportResponse implements TransportResponseInterface
{
    /**
     * @var ResponseInterface
     */
    private $guzzleResponse;

    /**
     * @param ResponseInterface $guzzleResponse
     */
    function __construct(ResponseInterface $guzzleResponse)
    {
        $this->guzzleResponse = $guzzleResponse;
    }

    /**
     * @return string The raw response string.
     * @author Mario Mueller
     */
    public function getBody()
    {
        return (string)$this->guzzleResponse->getBody();
    }
}

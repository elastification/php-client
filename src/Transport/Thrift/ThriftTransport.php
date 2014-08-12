<?php
namespace Dawen\Component\Elastic\Transport\Thrift;

use Dawen\Component\Elastic\Transport\TransportInterface;
use Dawen\Component\Elastic\Transport\TransportRequestInterface;
use Elasticsearch\RestClient;
use Elasticsearch\RestResponse;

/**
 * ${CARET}
 * @package Dawen\Component\Elastic\Transport\Thrift
 * @author Mario Mueller <mueller@freshcells.de>
 * @since 2014-08-12
 * @version 1.0.0
 */
class ThriftTransport implements TransportInterface
{
    /**
     * @var RestClient
     */
    private $thriftClient;

    /**
     * @param RestClient $thriftClient
     */
    function __construct(RestClient $thriftClient)
    {
        $this->thriftClient = $thriftClient;
    }

    /**
     * @param string $httpMethod The http method to use.
     * @return \Dawen\Component\Elastic\Transport\TransportRequestInterface
     * @author Mario Mueller
     */
    public function createRequest($httpMethod)
    {
        return new ThriftRequest($httpMethod);
    }

    /**
     * @param TransportRequestInterface $request The configured request to send.
     * @return \Dawen\Component\Elastic\Transport\TransportResponseInterface
     * @author Mario Mueller
     */
    public function send(TransportRequestInterface $request)
    {
        $response = $this->thriftClient->execute($request->getWrappedRequest());
        /* @var $response RestResponse */

    }
}

<?php
namespace Elastification\Client\Transport\Thrift;

use Elastification\Client\Transport\Exception\TransportLayerException;
use Elastification\Client\Transport\TransportInterface;
use Elastification\Client\Transport\TransportRequestInterface;
use Elasticsearch\RestClient;
use Elasticsearch\RestResponse;

/**
 * ${CARET}
 * @package Elastification\Client\Transport\Thrift
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
     * @return \Elastification\Client\Transport\TransportRequestInterface
     * @author Mario Mueller
     */
    public function createRequest($httpMethod)
    {
        return new ThriftRequest($httpMethod);
    }

    /**
     * @param TransportRequestInterface $request The configured request to send.
     * @return \Elastification\Client\Transport\TransportResponseInterface
     * @author Mario Mueller
     */
    public function send(TransportRequestInterface $request)
    {
        try {
            return new ThriftResponse($this->thriftClient->execute($request->getWrappedRequest()));
        } catch (\Exception $exception) {
            throw new TransportLayerException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}

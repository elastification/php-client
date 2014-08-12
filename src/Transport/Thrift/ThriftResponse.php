<?php
namespace Dawen\Component\Elastic\Transport\Thrift;

use Dawen\Component\Elastic\Transport\TransportResponseInterface;
use Elasticsearch\RestResponse;

/**
 * @package Dawen\Component\Elastic\Transport\Thrift
 * @author Mario Mueller
 * @since 2014-08-12
 * @version 1.0.0
 */
class ThriftResponse implements TransportResponseInterface
{
    /**
     * @var RestResponse
     */
    private $response;

    /**
     * @param RestResponse $response
     */
    function __construct(RestResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return string The raw response string.
     * @author Mario Mueller
     */
    public function getBody()
    {
        return $this->response->body;
    }
}

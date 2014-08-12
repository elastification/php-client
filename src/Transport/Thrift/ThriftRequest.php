<?php
namespace Dawen\Component\Elastic\Transport\Thrift;

use Dawen\Component\Elastic\Transport\TransportRequestInterface;
use Elasticsearch\RestRequest;

/**
 * @package Dawen\Component\Elastic\Transport\Thrift
 * @author Mario Mueller <mueller@freshcells.de>
 * @since 2014-08-12
 * @version 1.0.0
 */
class ThriftRequest implements TransportRequestInterface
{
    /**
     * @var RestRequest
     */
    private $request;

    /**
     * @param string $method The http method to use.
     * @param array $vals
     */
    function __construct($method, $vals = null)
    {
        $this->request = new RestRequest();
    }

    /**
     * @param string $body The raw request body.
     * @return void
     * @author Mario Mueller
     */
    public function setBody($body)
    {
        $this->request->body = $body;
    }

    /**
     * @param string $path The path according to the Elasticsearch http interface.
     * @return void
     * @author Mario Mueller
     */
    public function setPath($path)
    {
        $this->request->uri = $path;
    }

    /**
     * @return RestRequest
     * @author Mario Mueller
     */
    public function getWrappedRequest()
    {
        return $this->request;
    }
}

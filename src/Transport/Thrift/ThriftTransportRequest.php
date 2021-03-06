<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace Elastification\Client\Transport\Thrift;

use Elasticsearch\Method;
use Elasticsearch\RestRequest;
use Elastification\Client\Transport\TransportRequestInterface;

/**
 * @package Elastification\Client\Transport\Thrift
 * @author  Mario Mueller
 */
class ThriftTransportRequest implements TransportRequestInterface
{
    /**
     * @var RestRequest
     */
    private $request;

    /**
     * @param string $method The http method to use.
     * @param array  $vals
     */
    function __construct($method, $vals = null)
    {
        $this->request = new RestRequest($vals);
        $this->request->method = array_flip(Method::$__names)[$method];
    }

    /**
     * @param string $body The raw request body.
     *
     * @return void
     * @author Mario Mueller
     */
    public function setBody($body)
    {
        $this->request->body = $body;
    }

    /**
     * @param string $path The path according to the Elasticsearch http interface.
     *
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

    /**
     * @param array $params
     *
     * @return void
     * @author Mario Mueller
     */
    public function setQueryParams(array $params)
    {
        $this->request->parameters = $params;
    }

}

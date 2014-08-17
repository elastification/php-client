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

use Elastification\Client\Transport\Exception\TransportLayerException;
use Elastification\Client\Transport\TransportInterface;
use Elastification\Client\Transport\TransportRequestInterface;
use Elasticsearch\RestClient;

/**
 * Thrift transport implementation.
 *
 * This needs the elasticsearch thrift plugin to be installed on the server side.
 *
 * @package Elastification\Client\Transport\Thrift
 * @author Mario Mueller
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
        return new ThriftTransportRequest($httpMethod);
    }

    /**
     * @param TransportRequestInterface $request The configured request to send.
     * @return \Elastification\Client\Transport\TransportResponseInterface
     * @author Mario Mueller
     */
    public function send(TransportRequestInterface $request)
    {
        try {
            return new ThriftTransportResponse($this->thriftClient->execute($request->getWrappedRequest()));
        } catch (\Exception $exception) {
            throw new TransportLayerException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}

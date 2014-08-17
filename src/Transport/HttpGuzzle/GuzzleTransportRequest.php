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
namespace Elastification\Client\Transport\HttpGuzzle;

use Elastification\Client\Transport\TransportRequestInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Stream\Stream;

/**
 * @package Elastification\Client\Transport\HttpGuzzle
 * @author  Mario Mueller
 */
class GuzzleTransportRequest implements TransportRequestInterface
{
    /**
     * @var RequestInterface
     */
    private $guzzleRequest;

    /**
     * @param RequestInterface $guzzleRequest
     */
    public function __construct(RequestInterface $guzzleRequest)
    {
        $this->guzzleRequest = $guzzleRequest;
    }

    /**
     * @param string $body The raw request body.
     *
     * @return void
     * @author Mario Mueller
     */
    public function setBody($body)
    {
        $this->guzzleRequest->setBody(Stream::factory($body));
    }

    /**
     * @param string $path The path according to the Elasticsearch http interface.
     *
     * @return void
     * @author Mario Mueller
     */
    public function setPath($path)
    {
        $this->guzzleRequest->setPath($path);
    }

    /**
     * @return RequestInterface
     * @author Mario Mueller
     */
    public function getWrappedRequest()
    {
        return $this->guzzleRequest;
    }

    /**
     * @param array $params
     *
     * @return void
     * @author Mario Mueller
     */
    public function setQueryParams(array $params)
    {
        $this->guzzleRequest->setQuery($params);
    }
}

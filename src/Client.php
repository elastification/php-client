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
namespace Elastification\Client;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Transport\Exception\TransportLayerException;
use Elastification\Client\Transport\TransportInterface;
use Elastification\Client\Transport\TransportRequestInterface;

/**
 * The primary client class.
 *
 * This class is aware of the transport layer and the requests/response layer.
 * It serves as a kind of command pattern talking to a transport facade.
 *
 * @package Elastification\Client
 * @author  Daniel Wendlandt
 */
class Client implements ClientInterface
{
    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var Request\RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var null|string
     */
    private $elasticsearchVersion;

    /**
     * @param TransportInterface      $transport
     * @param RequestManagerInterface $requestManager
     * @param null|string             $elasticsearchVersion
     */
    public function __construct(
        TransportInterface $transport,
        RequestManagerInterface $requestManager,
        $elasticsearchVersion = null
    ) {
        $this->transport = $transport;
        $this->requestManager = $requestManager;

        if (null === $elasticsearchVersion) {
            $this->elasticsearchVersion = self::ELASTICSEARCH_VERSION_1_4_X;
        } else {
            $this->elasticsearchVersion = $elasticsearchVersion;
        }
    }

    /**
     * performs sending the request
     *
     * @param RequestInterface $request
     *
     * @throws Exception\ClientException
     * @throws Exception\RequestException
     * @return ResponseInterface
     * @author Daniel Wendlandt
     */
    public function send(RequestInterface $request)
    {
        if (!RequestMethods::isAllowed($request->getMethod())) {
            throw new RequestException('request method is not allowed');
        }

        $transportRequest = $this->transport->createRequest($request->getMethod());
        $transportRequest->setPath($this->generatePath($request));
        $transportRequest = $this->setBody($transportRequest, $request->getBody());
        $transportRequest = $this->setQueryParameters($transportRequest, $request->getParameters());

        try {
            $transportResponse = $this->transport->send($transportRequest);
        } catch (TransportLayerException $exception) {
            $clientException = new ClientException($exception->getMessage(), $exception->getCode(), $exception);
            throw $clientException;
        }

        $rawData = (string)$transportResponse->getBody();

        /** @var Response $response */
        $response = $request->createResponse($rawData, $request->getSerializer(), $request->getSerializerParams());
        $supportedClass = $request->getSupportedClass();

        $this->mangleSupportedClass($response, $supportedClass);

        return $response;
    }

    /**
     * @param ResponseInterface          $response
     * @param                     string $supportedClass
     *
     * @throws Exception\ClientException
     * @author Mario Mueller
     */
    private function mangleSupportedClass(ResponseInterface $response, $supportedClass)
    {
        if (!$response instanceof $supportedClass) {
            throw new ClientException('response is not an instance of ' . $supportedClass);
        }
    }

    /**
     * @param TransportRequestInterface $transportRequest
     * @param                           $body
     *
     * @return TransportRequestInterface
     * @author Mario Mueller
     */
    private function setBody(TransportRequestInterface $transportRequest, $body)
    {
        if (null !== $body) {
            $transportRequest->setBody($body);
        }

        return $transportRequest;
    }

    /**
     * Sets query params to transport request
     *
     * @param TransportRequestInterface $transportRequest
     * @param mixed $parameters
     * @return TransportRequestInterface
     * @author Daniel Wendlandt
     */
    private function setQueryParameters(TransportRequestInterface $transportRequest, $parameters)
    {
        if(!empty($parameters) && is_array($parameters)) {
            $transportRequest->setQueryParams($parameters);
        }

        return $transportRequest;
    }

    /**
     * @inheritdoc
     */
    public function getRequest($name)
    {
        return $this->requestManager->getRequest($name);
    }

    /**
     * Generates the path by given request
     *
     * @param RequestInterface $request
     *
     * @return string
     * @author Daniel Wendlandt
     */
    private function generatePath(RequestInterface $request)
    {
        $path = array();

        if (null !== $request->getIndex()) {
            $path[] = $request->getIndex();
        }

        if (null !== $request->getType()) {
            $path[] = $request->getType();
        }

        if (null !== $request->getAction()) {
            $path[] = $request->getAction();
        }

        return implode(self::PATH_DIVIDER, $path);
    }
}

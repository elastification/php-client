<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 07:42
 */

namespace Elastification\Client;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Transport\Exception\TransportLayerException;
use Elastification\Client\Transport\TransportInterface;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Stream\Stream;

/**
 * Class Client
 * @package Elastification\Client
 * @author Daniel Wendlandt
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

//todo think about an array of clients or a decision manager for get the right client (maybe voter patern)
    /**
     * @param TransportInterface $transport
     * @param RequestManagerInterface $requestManager
     * @param null|string $elasticsearchVersion
     */
    public function __construct(
        TransportInterface $transport,
        RequestManagerInterface $requestManager,
        $elasticsearchVersion = null)
    {
        $this->transport = $transport;
        $this->requestManager = $requestManager;

        if(null === $elasticsearchVersion) {
            $this->elasticsearchVersion = self::ELASTICSEARCH_VERSION_0_90_x;
        } else {
            $this->elasticsearchVersion = $elasticsearchVersion;
        }

    }

    /**
     * performs sending the request
     *
     * @param RequestInterface $request
     * @throws Exception\ClientException
     * @throws Exception\RequestException
     * @return ResponseInterface
     * @author Daniel Wendlandt
     */
    public function send(RequestInterface $request)
    {
        if(!RequestMethods::isAllowed($request->getMethod())) {
            throw new RequestException('request method is not allowed');
        }
        $transportRequest = $this->transport->createRequest($request->getMethod());
        $transportRequest->setPath($this->generatePath($request));

        $body = $request->getBody();
        if(null !== $body) {
            $transportRequest->setBody(Stream::factory($body));
        }

        try {
            $transportResponse = $this->transport->send($transportRequest);
        } catch(TransportLayerException $exception) {
            $clientException = new ClientException($exception->getMessage(), $exception->getCode(), $exception);
            throw $clientException;
        }

        $rawData = (string) $transportResponse->getBody();
        $response = $request->createResponse($rawData, $request->getSerializer(), $request->getSerializerParams());

        //todo check instance response and supprtedClass
        return $response;
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
     * @return string
     * @author Daniel Wendlandt
     */
    private function generatePath(RequestInterface $request)
    {
        $path = array();

        if(null !== $request->getIndex()) {
            $path[] = $request->getIndex();
        }

        if(null !== $request->getType()) {
            $path[] = $request->getType();
        }

        if(null !== $request->getAction()) {
            $path[] = $request->getAction();
        }

        return implode(self::PATH_DIVIDER, $path);
    }

}

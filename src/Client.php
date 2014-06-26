<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 07:42
 */

namespace Dawen\Component\Elastic;

use Dawen\Component\Elastic\Exception\ClientException;
use Dawen\Component\Elastic\Exception\RequestException;
use Dawen\Component\Elastic\Request\RequestInterface;
use Dawen\Component\Elastic\Request\RequestManagerInterface;
use Dawen\Component\Elastic\Request\RequestMethods;
use Dawen\Component\Elastic\Response\ResponseInterface;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Stream\Stream;

class Client implements ClientInterface
{

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $guzzleClient;

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
     * @param GuzzleClientInterface $guzzleClient
     * @param RequestManagerInterface $requestManager
     * @param null|string $elasticsearchVersion
     */
    public function __construct(
        GuzzleClientInterface $guzzleClient,
        RequestManagerInterface $requestManager,
        $elasticsearchVersion = null)
    {
        $this->guzzleClient = $guzzleClient;
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
     */
    public function send(RequestInterface $request)
    {
        if(!RequestMethods::isAllowed($request->getMethod())) {
            throw new RequestException('request method is not allowed');
        }
        $guzzleRequest = $this->guzzleClient->createRequest($request->getMethod());
        $guzzleRequest->setPath($this->generatePath($request));

        $body = $request->getBody();
        if(null !== $body) {
            $guzzleRequest->setBody(Stream::factory($body));
        }

        try {
            $guzzleResponse = $this->guzzleClient->send($guzzleRequest);
        } catch(\Exception $exception) {
            $clientException = new ClientException($exception->getMessage(), $exception->getCode(), $exception);
            throw $clientException;
        }

        $rawData = (string) $guzzleResponse->getBody();
        $response = $request->createResponse($rawData, $request->getSerializer(), $request->getSerializerParams());

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
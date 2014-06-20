<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 07:42
 */

namespace Dawen\Component\Elastic;

use Dawen\Component\Elastic\Exception\RequestException;
use Dawen\Component\Elastic\Request\RequestInterface;
use Dawen\Component\Elastic\Request\RequestManagerInterface;
use Dawen\Component\Elastic\Request\RequestMethods;
use Dawen\Component\Elastic\Response\ResponseInterface;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

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
     * @param GuzzleClientInterface $guzzleClient
     * @param RequestManagerInterface $requestManager
     * @internal param \GuzzleHttp\ClientInterface $guzzle
     */
    public function __construct(GuzzleClientInterface $guzzleClient, RequestManagerInterface $requestManager)
    {
        $this->guzzleClient = $guzzleClient;
        $this->requestManager = $requestManager;
    }

    /**
     * performs sending the request
     *
     * @param RequestInterface $request
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

        //todo set body. RequestINterface needs body

        $response = $this->guzzleClient->send($guzzleRequest);

        var_dump($response);
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
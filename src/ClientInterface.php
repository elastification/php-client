<?php

namespace Dawen\Component\Elastic;

use Dawen\Component\Elastic\Request\RequestInterface;
use Dawen\Component\Elastic\Response\ResponseInterface;

interface ClientInterface
{
    const VERSION = '0.1.0';

    const PATH_DIVIDER = '/';

    //todo throw exception in send
    /**
     * performs sending the request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);

    /**
     * Gets a pre defined request.
     *
     * @param string $name
     * @return RequestInterface
     */
    public function getRequest($name);
}
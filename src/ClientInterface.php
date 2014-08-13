<?php

namespace Elastification\Client;

use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Response\ResponseInterface;

interface ClientInterface
{
    const VERSION = '0.1.0';

    const PATH_DIVIDER = '/';

    const ELASTICSEARCH_VERSION_0_90_x = '0.90.x';

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
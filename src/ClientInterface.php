<?php

namespace Elastification\Client;

use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Response\ResponseInterface;

/**
 * Interface ClientInterface
 *
 * @package Elastification\Client
 * @author  Daniel Wendlandt
 */
interface ClientInterface
{
    /**
     * @var string
     */
    const VERSION = '0.1.0';

    /**
     * @var string
     */
    const PATH_DIVIDER = '/';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_0_90_x = '0.90.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_0_x = '1.0.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_1_x = '1.1.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_2_x = '1.2.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_3_x = '1.3.x';

    /**
     * Sends the request through the configured transport.
     *
     * @param RequestInterface $request
     *
     * @throws Exception\ClientException
     * @throws Exception\RequestException
     * @return ResponseInterface
     * @author Daniel Wendlandt
     */
    public function send(RequestInterface $request);

    /**
     * Gets a predefined and registered request.
     *
     * @param string $name
     *
     * @return RequestInterface
     * @author Daniel Wendlandt
     */
    public function getRequest($name);
}

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
    const ELASTICSEARCH_VERSION_0_90_X = '0.90.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_0_X = '1.0.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_1_X = '1.1.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_2_X = '1.2.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_3_X = '1.3.x';

    /**
     * @var string
     */
    const ELASTICSEARCH_VERSION_1_4_X = '1.4.x';

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

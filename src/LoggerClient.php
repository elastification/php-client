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
use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Response\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * The primary client class.
 *
 * This class is aware of the transport layer and the requests/response layer.
 * It serves as a kind of command pattern talking to a transport facade.
 *
 * @package Elastification\Client
 * @author  Daniel Wendlandt
 */
class LoggerClient implements ClientInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientInterface $client,
        LoggerInterface $logger
    ) {
       $this->client = $client;
        $this->logger = $logger;
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
        $this->logger->info('request: '. get_class($request), $this->generateLogRequestData($request));

        try {
            $timeTaken = microtime(true);
            $response = $this->client->send($request);

            $this->logger->debug(
                'time taken: ' .
                (microtime(true) - $timeTaken) .
                's (' . get_class($request) . ')');

            $this->logger->debug('response: ' . get_class($response), $this->generateLogResponseData($response));

            return $response;
        } catch(ClientException $exception) {
            $this->logger->error($exception->getMessage(),
                array(
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                    'message' => $exception->getMessage(),

                ));

            throw $exception;
        }
    }


    /**
     * @inheritdoc
     */
    public function getRequest($name)
    {
        return $this->client->getRequest($name);
    }

    /**
     * generates an array with all request information in it.
     *
     * @param RequestInterface $request
     * @return array
     * @author Daniel Wendlandt
     */
    private function generateLogRequestData(RequestInterface $request) {
        return array(
            'class' => get_class($request),
            'method' => $request->getMethod(),
            'index' => $request->getIndex(),
            'type' => $request->getType(),
            'action' => $request->getAction(),
            'response_class' => $request->getSupportedClass(),
            'body' => $request->getBody()
        );
    }

    /**
     * generates an array with all response information in it.
     *
     * @param ResponseInterface $response
     * @return array
     * @author Daniel Wendlandt
     */
    private function generateLogResponseData(ResponseInterface $response) {
        return array(
            'class' => get_class($response),
            'raw_data' => $response->getRawData()

        );
    }

}

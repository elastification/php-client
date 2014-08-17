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
namespace Elastification\Client\Transport\Thrift;

use Elasticsearch\RestClient;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TSocket;

/**
 * Factory class to create a thrift connection.
 *
 * Most of this factory code is borrowed from Ruflin's Elastica client.
 * {@see https://github.com/ruflin/Elastica/blob/master/lib/Elastica/Transport/Thrift.php}.
 *
 * @package Elastification\Client\Transport\Thrift
 * @author  Mario Mueller
 */
class ThriftTransportConnectionFactory
{
    /**
     * @param string  $host
     * @param integer $port
     * @param null    $sendTimeout
     * @param null    $receiveTimeout
     * @param bool    $framedTransport
     *
     * @return RestClient
     * @author Mario Mueller
     */
    public static function factory($host, $port, $sendTimeout = null, $receiveTimeout = null, $framedTransport = false)
    {
        $socket = self::createSocket($host, $port, $sendTimeout, $receiveTimeout);
        $transport = self::createTransport($socket, $framedTransport);
        $protocol = new TBinaryProtocolAccelerated($transport);
        $client = new RestClient($protocol);
        $transport->open();

        return $client;
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $sendTimeout
     * @param int $receiveTimeout
     *
     * @return TSocket
     * @author Mario Mueller
     */
    private static function createSocket($host, $port, $sendTimeout, $receiveTimeout)
    {
        $socket = new TSocket($host, $port, true);
        if (null !== $sendTimeout) {
            $socket->setSendTimeout($sendTimeout);
        }

        if (null !== $receiveTimeout) {
            $socket->setRecvTimeout($receiveTimeout);
        }

        return $socket;
    }

    /**
     * @param TSocket $socket
     * @param bool    $framedTransport
     *
     * @return TBufferedTransport|TFramedTransport
     * @author Mario Mueller
     */
    private static function createTransport(TSocket $socket, $framedTransport = false)
    {
        if ($framedTransport) {
            $transport = new TFramedTransport($socket);
        } else {
            $transport = new TBufferedTransport($socket);
        }
        return $transport;
    }
}

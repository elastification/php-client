<?php
namespace Dawen\Component\Elastic\Transport\Thrift;

use Elasticsearch\RestClient;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TSocket;

/**
 * @package Dawen\Component\Elastic\Transport\Thrift
 * @author Mario Mueller
 * @since 2014-08-12
 * @version 1.0.0
 */
class ThriftConnectionFactory
{
    /**
     * @param string $host
     * @param integer $port
     * @param null $sendTimeout
     * @param null $recvTimeout
     * @param bool $framedTransport
     * @return RestClient
     * @author Mario Mueller
     */
    public static function factory($host, $port, $sendTimeout = null, $recvTimeout = null, $framedTransport = false)
    {
        $socket = new TSocket($host, $port, true);
        if (null !== $sendTimeout) {
            $socket->setSendTimeout($sendTimeout);
        }

        if (null !== $recvTimeout) {
            $socket->setRecvTimeout($recvTimeout);
        }

        if ($framedTransport) {
            $transport = new TFramedTransport($socket);
        } else {
            $transport = new TBufferedTransport($socket);
        }

        $protocol = new TBinaryProtocolAccelerated($transport);
        $client = new RestClient($protocol);
        $transport->open();

        return $client;
    }
}

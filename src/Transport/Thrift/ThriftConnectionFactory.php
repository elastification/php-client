<?php
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
class ThriftConnectionFactory
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
        $socket = new TSocket($host, $port, true);
        if (null !== $sendTimeout) {
            $socket->setSendTimeout($sendTimeout);
        }

        if (null !== $receiveTimeout) {
            $socket->setRecvTimeout($receiveTimeout);
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

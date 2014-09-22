<?php
namespace Elastification\Client\Tests\Integration;

use Elasticsearch\RestClient;
use Elastification\Client\Client;
use Elastification\Client\ClientInterface;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\V1x\CreateDocumentRequest;
use Elastification\Client\Response\V1x\CreateUpdateDocumentResponse;
use Elastification\Client\Serializer\NativeJsonSerializer;
use Elastification\Client\Transport\Thrift\ThriftTransportConnectionFactory;
use Elastification\Client\Transport\Thrift\ThriftTransport;

/**
 * @package Elastification\Client\Tests\Integration
 * @author  Mario Mueller
 */
class SandboxThriftV1xTest extends \PHPUnit_Framework_TestCase
{

    const INDEX = 'elastification';
    const TYPE = 'sandbox';

    /**
     *
     * @var RestClient
     */
    private $thriftClient;

    /**
     * @author Mario Mueller
     */
    public function setUp()
    {
        $this->thriftClient = ThriftTransportConnectionFactory::factory('localhost', 9500);
    }

    /**
     * @throws \Elastification\Client\Exception\ClientException
     * @throws \Elastification\Client\Exception\RequestException
     * @author Mario Mueller
     */
    public function testThriftConnection()
    {
        $thriftTransport = new ThriftTransport($this->thriftClient);
        $requestManager = new RequestManager();
        $client = new Client($thriftTransport, $requestManager, ClientInterface::ELASTICSEARCH_VERSION_1_3_X);
        $serializer = new NativeJsonSerializer();
        $createDocumentRequest = new CreateDocumentRequest(self::INDEX, self::TYPE, $serializer, ['assoc' => true]);
        $createDocumentRequest->setBody(
            array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000))
        );
        /** @var CreateUpdateDocumentResponse $response */
        $timeStart = microtime(true);
        $response = $client->send($createDocumentRequest);
        echo 'createDocument: ' . (microtime(true) - $timeStart) . 's' . PHP_EOL;

        $this->assertSame(self::INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->isOk());
        $this->assertTrue(strlen($response->getId()) > 5);
    }
}

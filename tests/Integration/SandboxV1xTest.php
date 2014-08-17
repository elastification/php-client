<?php
namespace Elastification\Client\Tests\Integration;

use Elastification\Client\Client;
use Elastification\Client\ClientInterface;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\V1x\CreateDocumentRequest;
use Elastification\Client\Response\V1x\CreateUpdateDocumentResponse;
use Elastification\Client\Serializer\NativeJsonSerializer;
use Elastification\Client\Transport\Thrift\ThriftConnectionFactory;
use Elastification\Client\Transport\Thrift\ThriftTransport;

/**
 * ${CARET}
 *
 * @package Elastification\Client\Tests\Integration
 * @author  Mario Mueller <mueller@freshcells.de>
 * @since   2014-08-13
 * @version 1.0.0
 */
class SandboxV1xTest extends \PHPUnit_Framework_TestCase
{

    const INDEX = 'elastification';
    const TYPE = 'sandbox';

    private $thriftClient;

    public function setUp()
    {
        $this->thriftClient = ThriftConnectionFactory::factory('localhost', 9500);
    }

    public function testThriftConnection()
    {

        $thriftTransport = new ThriftTransport($this->thriftClient);
        $requestManager = new RequestManager();
        $client = new Client($thriftTransport, $requestManager, ClientInterface::ELASTICSEARCH_VERSION_1_3_x);
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

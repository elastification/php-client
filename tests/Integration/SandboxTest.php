<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 23/06/14
 * Time: 16:08
 */

namespace Dawen\Component\Elastic\Tests\Integration;

use Dawen\Component\Elastic\Request\RequestManager;
use Dawen\Component\Elastic\Request\RequestManagerInterface;
use Dawen\Component\Elastic\Request\Shared\CreateDocumentRequest;
use Dawen\Component\Elastic\Request\Shared\SearchRequest;
use Dawen\Component\Elastic\Serializer\NativeJsonSerializer;
use Dawen\Component\Elastic\Serializer\SerializerInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Dawen\Component\Elastic\Client;
use Dawen\Component\Elastic\ClientInterface;

class SandboxTest extends \PHPUnit_Framework_TestCase
{

    private $url = 'http://localhost:9200/';

    /**
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;


    protected function setUp()
    {
        parent::setUp();

        $this->guzzleClient = new GuzzleClient(array('base_url' => $this->url));
        $this->requestManager = new RequestManager();
        $this->client = new Client($this->guzzleClient, $this->requestManager);
        $this->serializer = new NativeJsonSerializer();

    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->guzzleClient = null;
        $this->requestManager = null;
        $this->client = null;
    }

    public function testCreateDocument()
    {
        $timeStart = microtime(true);
//        $createDocumentRequest = new SearchRequest('dawen-elastic', 'sandbox', $this->serializer);
        $createDocumentRequest = new CreateDocumentRequest('dawen-elastic', 'sandbox', $this->serializer);
//        $this->assertInstanceOf('Dawen\Component\Elastic\ClientInterface', $this->client);

        $createDocumentRequest->setBody(array('name' => 'test'.rand(100,10000), 'value' => 'myTestVal'.rand(100,10000)));
        $response = $this->client->send($createDocumentRequest);

        var_dump(microtime(true) - $timeStart);
    }
}
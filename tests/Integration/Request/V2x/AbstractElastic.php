<?php
namespace Elastification\Client\Tests\Integration\Request\V2x;

use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\RequestManager;
use Elastification\Client\Request\RequestManagerInterface;
use Elastification\Client\Request\V2x\CreateDocumentRequest;
use Elastification\Client\Request\V2x\GetDocumentRequest;
use Elastification\Client\Request\V2x\Index\CreateIndexRequest;
use Elastification\Client\Request\V2x\Index\DeleteIndexRequest;
use Elastification\Client\Request\V2x\Index\IndexExistsRequest;
use Elastification\Client\Request\V2x\Index\RefreshIndexRequest;
use Elastification\Client\Response\V2x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V2x\DocumentResponse;
use Elastification\Client\Serializer\NativeJsonSerializer;
use Elastification\Client\Serializer\SerializerInterface;
use Elastification\Client\Transport\HttpGuzzle\GuzzleTransport;
use Elastification\Client\Transport\TransportInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Elastification\Client\Client;
use Elastification\Client\ClientInterface;

abstract class AbstractElastic extends \PHPUnit_Framework_TestCase
{

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

    /**
     * @var TransportInterface
     */
    private $transportClient;


    protected function setUp()
    {
        parent::setUp();

        $this->guzzleClient = new GuzzleClient(array('base_uri' => V2X_URL));
        $this->transportClient = new GuzzleTransport($this->guzzleClient);
        $this->requestManager = new RequestManager();
        $this->client = new Client($this->transportClient, $this->requestManager);
        $this->serializer = new NativeJsonSerializer();
    }

    protected function tearDown()
    {
        parent::tearDown();

        if($this->hasIndex()) {
            $this->deleteIndex();
        }

        $this->guzzleClient = null;
        $this->requestManager = null;
        $this->client = null;
        $this->serializer = null;
        $this->transportClient = null;
    }

    /**
     * Getter for client
     *
     * @return ClientInterface
     * @author Daniel Wendlandt
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * Getter for serializer
     *
     * @return SerializerInterface
     * @author Daniel Wendlandt
     */
    protected function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * Creates a new index on elasticsearch machine/cluster
     *
     * @param null|string $index
     */
    protected function createIndex($index = null)
    {
        if(null === $index) {
            $index = ES_INDEX;
        }

        $createIndexRequest = new CreateIndexRequest($index, null, $this->serializer);
        $this->client->send($createIndexRequest);
    }

    /**
     * Checks if an index exists
     *
     * @param null|string $index
     *
     * @return bool
     */
    protected function hasIndex($index = null)
    {
        if(null === $index) {
            $index = ES_INDEX;
        }

        $indexExistsRequest = new IndexExistsRequest($index, null, $this->serializer);
        try {
            $this->client->send($indexExistsRequest);
            return true;
        } catch(ClientException $exception) {
            return false;
        }

    }

    /**
     * Deletes an index
     *
     * @param null|string $index
     */
    protected function deleteIndex($index = null)
    {
        if(null === $index) {
            $index = ES_INDEX;
        }

        $deleteIndexRequest = new DeleteIndexRequest($index, null, $this->serializer);
        $this->client->send($deleteIndexRequest);
    }

    /**
     * Refreshes an index
     *
     * @param null|string $index
     */
    protected function refreshIndex($index = null)
    {
        if(null === $index) {
            $index = ES_INDEX;
        }

        $refreshIndexRequest = new RefreshIndexRequest($index, null, $this->serializer);
        $this->client->send($refreshIndexRequest);
    }

    /**
     * Creates on document for a type
     * If no data is given a random dataset will be created with properties: name, value
     *
     * @param string $type
     * @param null|array|object $data
     *
     * @return string
     */
    protected function createDocument($type, $data = null)
    {
        $createDocumentRequest = new CreateDocumentRequest(ES_INDEX, $type, $this->serializer);
        if(null === $data) {
            $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        }

        $createDocumentRequest->setBody($data);
        /** @var CreateUpdateDocumentResponse $response */
        $response = $this->client->send($createDocumentRequest);

        return $response->getId();
    }

    /**
     * Gets the source of a single document
     *
     * @param string $index
     * @param string $type
     * @param string $id
     * @return array|object
     * @throws \Elastification\Client\Exception\RequestException
     * @throws \Elastification\Client\Exception\ResponseException
     */
    protected function getDocument($index, $type, $id)
    {
        $getDocumentRequest = new GetDocumentRequest($index, $type, $this->serializer);
        $getDocumentRequest->setId($id);

        /** @var DocumentResponse $response */
        $response = $this->client->send($getDocumentRequest);

        return $response->getSource();
    }
}

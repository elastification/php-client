<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:11
 */

namespace Elastification\Client\Repository;

use Elastification\Client\ClientInterface;
use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Serializer\SerializerInterface;

class DocumentRepository implements DocumentRepositoryInterface
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $versionFolder;

    /**
     * @var RepositoryClassMap
     */
    private $repositoryClassMap;

    /**
     * @var RequestRepositoryFactoryInterface
     */
    private $requestRepositoryFactory;

    /**
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     * @param RepositoryClassMapInterface $repositoryClassMap
     * @param string $versionFolder
     */
    public function __construct(ClientInterface $client,
                                SerializerInterface $serializer,
                                RepositoryClassMapInterface $repositoryClassMap = null,
                                $versionFolder = ClientVersionMapInterface::VERSION_V1X,
                                RequestRepositoryFactoryInterface $requestRepositoryFactory = null)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->versionFolder = $versionFolder;

        if (null === $repositoryClassMap) {
            $this->repositoryClassMap = new RepositoryClassMap($versionFolder);
        } else {
            $this->repositoryClassMap = $repositoryClassMap;
        }

        if(null === $requestRepositoryFactory) {
            $this->requestRepositoryFactory = new RequestRepositoryFactory();
        } else {
            $this->requestRepositoryFactory = $requestRepositoryFactory;
        }
    }

    /**
     * creates a document
     *
     * @param string $index
     * @param string $type
     * @param mixed $document
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function create($index, $type, $document)
    {
        $request = $this->createRequestInstance(self::CREATE_DOCUMENT, $index, $type);
        $request->setBody($document);

        return $this->client->send($request);
    }

    /**
     * Deletes a document by id
     *
     * @param string $index
     * @param string $type
     * @param string $id
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function delete($index, $type, $id)
    {
        $request = $this->createRequestInstance(self::DELETE_DOCUMENT, $index, $type);
        $request->setId($id);

        return $this->client->send($request);
    }

    /**
     * gets a document by id
     *
     * @param string $index
     * @param string $type
     * @param string $id
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function get($index, $type, $id)
    {
        $request = $this->createRequestInstance(self::GET_DOCUMENT, $index, $type);
        $request->setId($id);

        return $this->client->send($request);
    }

    /**
     * @param string $index
     * @param string $type
     * @param string $id
     * @param mixed $document
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function update($index, $type, $id, $document)
    {
        $request = $this->createRequestInstance(self::UPDATE_DOCUMENT, $index, $type);
        $request->setId($id);
        $request->setBody($document);

        return $this->client->send($request);
    }

    /**
     * gets the right class string of a version
     *
     * @param string $class
     * @return string
     * @author Daniel Wendlandt
     */
    private function getClass($class)
    {
        return $this->repositoryClassMap->getClassName($class);
    }

    /**
     * creates an request instance
     *
     * @param string $requestName
     * @param string $index
     * @param string $type
     * @return RequestInterface
     * @author Daniel Wendlandt
     */
    private function createRequestInstance($requestName, $index, $type)
    {
        $class = $this->getClass($requestName);
        /** @var RequestInterface $request */
        return $this->requestRepositoryFactory->create($class, $index, $type, $this->serializer);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:11
 */

namespace Elastification\Client\Repository;

use Elastification\Client\ClientInterface;
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
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     * @param RepositoryClassMapInterface $repositoryClassMap
     * @param string $versionFolder
     */
    public function __construct(ClientInterface $client,
                                SerializerInterface $serializer,
                                RepositoryClassMapInterface $repositoryClassMap = null,
                                $versionFolder = RepositoryClassMapInterface::VERSION_V1X)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->versionFolder = $versionFolder;

        if (null === $repositoryClassMap) {
            $this->repositoryClassMap = new RepositoryClassMap($versionFolder);
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
        $class = $this->getClass(self::CREATE_DOCUMENT);
        /** @var RequestInterface $request */
        $request = new $class($index, $type, $this->serializer);
        $request->setBody($document);

        return $this->client->send($request);
    }

    /**
     * @param string $index
     * @param string $type
     * @param string $id
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function delete($index, $type, $id)
    {
        $class = $this->getClass(self::DELETE_DOCUMENT);
        /** @var RequestInterface $request */
        $request = new $class($index, $type, $this->serializer);
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
        $class = $this->getClass(self::GET_DOCUMENT);
        /** @var RequestInterface $request */
        $request = new $class($index, $type, $this->serializer);
        $request->setId($id);

        return $this->client->send($request);
    }

    /**
     * gets the right class string of a version
     *
     * @param $class
     * @return string
     * @author Daniel Wendlandt
     */
    private function getClass($class)
    {
        return $this->repositoryClassMap->getClassName($class);
    }

}
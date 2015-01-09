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

abstract class AbstractRepository
{

    /**
     * @var ClientInterface
     */
    protected $client;

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
     * @param RequestRepositoryFactoryInterface $requestRepositoryFactory
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
     * creates an request instance
     *
     * @param string $requestName
     * @param string $index
     * @param string $type
     * @return RequestInterface
     * @author Daniel Wendlandt
     */
    protected function createRequestInstance($requestName, $index, $type)
    {
        $class = $this->getClass($requestName);
        /** @var RequestInterface $request */
        return $this->requestRepositoryFactory->create($class, $index, $type, $this->serializer);
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

}
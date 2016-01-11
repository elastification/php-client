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
    protected $versionFolder;

    /**
     * @var RepositoryClassMap
     */
    protected $repositoryClassMap;

    /**
     * @var RequestRepositoryFactoryInterface
     */
    private $requestRepositoryFactory;

    /**
     * @param ClientInterface                   $client
     * @param SerializerInterface               $serializer
     * @param RepositoryClassMapInterface       $repositoryClassMap
     * @param string                            $versionFolder
     * @param RequestRepositoryFactoryInterface $requestRepositoryFactory
     */
    public function __construct(
        ClientInterface $client,
        SerializerInterface $serializer,
        RepositoryClassMapInterface $repositoryClassMap = null,
        $versionFolder = ClientVersionMapInterface::VERSION_V1X,
        RequestRepositoryFactoryInterface $requestRepositoryFactory = null
    ) {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->versionFolder = (string)$versionFolder;

        if (null === $repositoryClassMap) {
            $this->repositoryClassMap = new RepositoryClassMap($versionFolder);
        } else {
            $this->repositoryClassMap = $repositoryClassMap;
        }

        if (null === $requestRepositoryFactory) {
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
     *
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
     *
     * @return string
     * @author Daniel Wendlandt
     */
    protected function getClass($class)
    {
        return $this->repositoryClassMap->getClassName($class);
    }
}

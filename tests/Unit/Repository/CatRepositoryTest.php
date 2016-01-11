<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:43
 */

namespace Elastification\Client\Tests\Unit\Repository;

use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Exception\ClientException;
use Elastification\Client\Exception\RepositoryException;
use Elastification\Client\Repository\CatRepository;
use Elastification\Client\Repository\CatRepositoryInterface;

class CatRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryClassMap;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestRepositoryFactory;

    /**
     * @var CatRepositoryInterface
     */
    private $indexRepository;

    protected function setUp()
    {
        parent::setUp();
        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client = $this->getMockBuilder('Elastification\Client\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryClassMap = $this->getMockBuilder('Elastification\Client\Repository\RepositoryClassMapInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory = $this->getMockBuilder('Elastification\Client\Repository\RequestRepositoryFactoryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->indexRepository = new CatRepository($this->client,
            $this->serializer,
            $this->repositoryClassMap,
            ClientVersionMapInterface::VERSION_V1X,
            $this->requestRepositoryFactory);
    }

    protected function tearDown()
    {
        $this->serializer = null;
        $this->client = null;
        $this->repositoryClassMap = null;
        $this->indexRepository = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\Repository\AbstractRepository', $this->indexRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\CatRepositoryInterface', $this->indexRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\CatRepository', $this->indexRepository);
    }

    public function testConstructor()
    {
        $searchRepository = new CatRepository($this->client, $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Repository\AbstractRepository', $this->indexRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\CatRepositoryInterface', $searchRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\CatRepository', $searchRepository);
    }

    public function testAliases()
    {
        $return = 'itsMe';
        $type = null;
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo($className),
                $this->equalTo(null),
                $this->equalTo(null),
                $this->equalTo($this->serializer))
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(CatRepositoryInterface::CAT_ALIASES)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->aliases();

        $this->assertSame($return, $result);
    }

    public function testAliasesException()
    {
        $this->indexRepository = new CatRepository($this->client,
            $this->serializer,
            $this->repositoryClassMap,
            ClientVersionMapInterface::VERSION_V090X,
            $this->requestRepositoryFactory);

        $this->requestRepositoryFactory->expects($this->never())
            ->method('create');

        $this->repositoryClassMap->expects($this->never())
            ->method('getClassName');

        $this->client->expects($this->never())
            ->method('send');


        $this->setExpectedException('Elastification\Client\Exception\RepositoryException',
            'Version folder V090x is not allowed for cat repository');

        $this->indexRepository->aliases();
    }

}
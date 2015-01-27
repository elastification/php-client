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
use Elastification\Client\Repository\IndexRepository;
use Elastification\Client\Repository\IndexRepositoryInterface;

class IndexRepositoryTest extends \PHPUnit_Framework_TestCase
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
     * @var IndexRepositoryInterface
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

        $this->indexRepository = new IndexRepository($this->client,
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
        $this->assertInstanceOf('Elastification\Client\Repository\IndexRepositoryInterface', $this->indexRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\IndexRepository', $this->indexRepository);
    }

    public function testConstructor()
    {
        $searchRepository = new IndexRepository($this->client, $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Repository\AbstractRepository', $this->indexRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\IndexRepositoryInterface', $searchRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\IndexRepository', $searchRepository);
    }

    public function testExistsWithReturnTrue()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $type = null;
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_EXIST)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->exists($index);

        $this->assertTrue($result);
    }

    public function testExistsWithReturnFalse()
    {
        $index = 'myIndex';
        $type = null;
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_EXIST)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willThrowException(new ClientException('test throw'));

        $result = $this->indexRepository->exists($index);

        $this->assertFalse($result);
    }

    public function testCreate()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $type = null;
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_CREATE)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->create($index);

        $this->assertSame($return, $result);
    }

    public function testDelete()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $type = null;
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_DELETE)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->delete($index);

        $this->assertSame($return, $result);
    }

    public function testRefresh()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $type = null;
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_REFRESH)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->refresh($index);

        $this->assertSame($return, $result);
    }

}
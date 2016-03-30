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
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
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
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
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
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
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

    public function testCreateWithBody()
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
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_CREATE)
            ->willReturn($className);

        $body = ['foo' => 'bar'];

        $request->expects($this->once())
            ->method('setBody')
            ->with($body);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->create($index, $body);

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
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
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
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
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

    public function testGetMapping()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $type = 'myType';
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_GET_MAPPING)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->getMapping($index, $type);

        $this->assertSame($return, $result);
    }

    public function testGetMappingWihtoutType()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo(null),
                $this->equalTo($this->serializer))
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_GET_MAPPING)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->getMapping($index);

        $this->assertSame($return, $result);
    }

    public function testGetMappingWihtoutParams()
    {
        $return = 'itsMe';
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
            ->with(IndexRepositoryInterface::INDEX_GET_MAPPING)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->getMapping();

        $this->assertSame($return, $result);
    }

    public function testCreateMapping()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $type = 'myType';
        $className = 'myClassName';
        $mapping = array('index' => 'mapping');

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())->method('setBody')->with($this->equalTo($mapping));

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo($type),
                $this->equalTo($this->serializer))
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_CREATE_MAPPING)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->createMapping($mapping, $index, $type);

        $this->assertSame($return, $result);
    }

    public function testGetAliasesWihtoutParams()
    {
        $return = 'itsMe';
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
            ->with(IndexRepositoryInterface::INDEX_GET_ALIASES)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->getAliases();

        $this->assertSame($return, $result);
    }

    public function testGetAliasesWihtINdex()
    {
        $index = 'myIndex';
        $return = 'itsMe';
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo($className),
                $this->equalTo($index),
                $this->equalTo(null),
                $this->equalTo($this->serializer))
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(IndexRepositoryInterface::INDEX_GET_ALIASES)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->getAliases($index);

        $this->assertSame($return, $result);
    }

    public function testUpdateAliases()
    {
        $return = 'itsMe';
        $className = 'myClassName';
        $aliasActions = array('actions' => array('add' => array()));

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())->method('setBody')->with($this->equalTo($aliasActions));

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
            ->with(IndexRepositoryInterface::INDEX_UPDATE_ALIASES)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->indexRepository->updateAliases($aliasActions);

        $this->assertSame($return, $result);
    }

    public function testUpdateAliasesMissingActionsKeyException()
    {
        $aliasActions = array('actionsWrong' => array('add' => array()));

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->never())->method('setBody');
        $this->requestRepositoryFactory->expects($this->never())->method('create');
        $this->repositoryClassMap->expects($this->never())->method('getClassName');
        $this->client->expects($this->never())->method('send');

        try {
            $this->indexRepository->updateAliases($aliasActions);
        } catch(RepositoryException $exception) {
            $this->assertContains('Actions key is missing', $exception->getMessage());
            return;
        }

        $this->fail();
    }
}

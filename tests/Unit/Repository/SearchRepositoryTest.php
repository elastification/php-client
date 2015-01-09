<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:43
 */

namespace Elastification\Client\Tests\Unit\Repository;

use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Repository\SearchRepositoryInterface;
use Elastification\Client\Repository\SearchRepository;

class SearchRepositoryTest extends \PHPUnit_Framework_TestCase
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
     * @var SearchRepositoryInterface
     */
    private $searchRepository;

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

        $this->searchRepository = new SearchRepository($this->client,
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
        $this->searchRepository = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\Repository\AbstractRepository', $this->searchRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\SearchRepositoryInterface', $this->searchRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\SearchRepository', $this->searchRepository);
    }

    public function testConstructor()
    {
        $searchRepository = new SearchRepository($this->client, $this->serializer);
        $this->assertInstanceOf('Elastification\Client\Repository\AbstractRepository', $this->searchRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\SearchRepositoryInterface', $searchRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\SearchRepository', $searchRepository);
    }

    public function testSearch()
    {
        $index = 'myIndex';
        $type = 'myType';
        $return = 'itsMe';
        $query = array('test');
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('setBody')
            ->with($query);

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(SearchRepositoryInterface::SEARCH)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->searchRepository->search($index, $type, $query);

        $this->assertSame($return, $result);
    }

    public function testSearchWithoutQueryParam()
    {
        $index = 'myIndex';
        $type = 'myType';
        $return = 'itsMe';
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->never())
            ->method('setBody');

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(SearchRepositoryInterface::SEARCH)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->searchRepository->search($index, $type);

        $this->assertSame($return, $result);
    }


}
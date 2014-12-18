<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:43
 */

namespace Elastification\Client\Tests\Unit\Repository;

use Elastification\Client\Exception\RepositoryClassMapException;
use Elastification\Client\Repository\DocumentRepository;
use Elastification\Client\Repository\DocumentRepositoryInterface;
use Elastification\Client\Repository\RepositoryClassMap;
use Elastification\Client\Repository\RepositoryClassMapInterface;

class DocumentRepositoryTest extends \PHPUnit_Framework_TestCase
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
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

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

        $this->documentRepository = new DocumentRepository($this->client,
            $this->serializer,
            $this->repositoryClassMap,
            RepositoryClassMapInterface::VERSION_V1X,
            $this->requestRepositoryFactory);
    }

    protected function tearDown()
    {
        $this->serializer = null;
        $this->client = null;
        $this->repositoryClassMap = null;
        $this->documentRepository = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\Repository\DocumentRepositoryInterface', $this->documentRepository);
        $this->assertInstanceOf('Elastification\Client\Repository\DocumentRepository', $this->documentRepository);
    }

    public function testCreate()
    {
        $index = 'myIndex';
        $type = 'myType';
        $return = 'itsMe';
        $data = array('test');
        $className = 'myClassName';

        $request = $this->getMockBuilder('Elastification\Client\Request\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('setBody')
            ->with($data);

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(DocumentRepositoryInterface::CREATE_DOCUMENT)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->documentRepository->create($index, $type, $data);

        $this->assertSame($return, $result);
    }

    public function testGet()
    {
        $index = 'myIndex';
        $type = 'myType';
        $return = 'itsMe';
        $className = 'myClassName';
        $id = 'testId';

        $request = $this->getMockBuilder('Elastification\Client\Request\V1x\GetDocumentRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('setId')
            ->with($id);

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(DocumentRepositoryInterface::GET_DOCUMENT)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->documentRepository->get($index, $type, $id);

        $this->assertSame($return, $result);
    }

    public function testDelete()
    {
        $index = 'myIndex';
        $type = 'myType';
        $return = 'itsMe';
        $className = 'myClassName';
        $id = 'testId';

        $request = $this->getMockBuilder('Elastification\Client\Request\V1x\DeleteDocumentRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('setId')
            ->with($id);

        $this->requestRepositoryFactory->expects($this->once())
            ->method('create')
            ->with($className, $index, $type, $this->serializer)
            ->willReturn($request);

        $this->repositoryClassMap->expects($this->once())
            ->method('getClassName')
            ->with(DocumentRepositoryInterface::DELETE_DOCUMENT)
            ->willReturn($className);

        $this->client->expects($this->once())
            ->method('send')
            ->willReturn($return);

        $result = $this->documentRepository->delete($index, $type, $id);

        $this->assertSame($return, $result);
    }


}
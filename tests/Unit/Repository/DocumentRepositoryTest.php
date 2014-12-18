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

        $this->documentRepository = new DocumentRepository($this->client, $this->serializer);
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

}
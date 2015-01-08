<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:43
 */

namespace Elastification\Client\Tests\Unit\Repository;

use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Exception\RepositoryClassMapException;
use Elastification\Client\Repository\RepositoryClassMap;
use Elastification\Client\Repository\RepositoryClassMapInterface;

class RepositoryClassMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var RepositoryClassMapInterface
     */
    private $repositoryClassMap;

    protected function setUp()
    {
        parent::setUp();
        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        //$this->repositoryClassMap = new RepositoryClassMap();
    }

    protected function tearDown()
    {
        $this->serializer = null;
        $this->repositoryClassMap = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $repositoryClassMap = new RepositoryClassMap();
        $this->assertInstanceOf('Elastification\Client\Repository\RepositoryClassMapInterface', $repositoryClassMap);
        $this->assertInstanceOf('Elastification\Client\Repository\RepositoryClassMap', $repositoryClassMap);
    }

    public function testVersionExceptionInConstructor()
    {
        $version = 'NotExistingVersion';
        $class = 'NotExistingClass';

        try {
            $repositoryClassMap = new RepositoryClassMap($version);
            $repositoryClassMap->getClassName($class);
        } catch(RepositoryClassMapException $exception) {
            $this->assertEquals('Version folder "' . $version  . '" is not defined', $exception->getMessage());
        }
    }

    public function testConstructor()
    {
        $class = 'GetDocumentRequest';

        $repositoryClassMapV090x = new RepositoryClassMap(ClientVersionMapInterface::VERSION_V090X);
        $className = $repositoryClassMapV090x->getClassName($class);
        $this->assertContains(ClientVersionMapInterface::VERSION_V090X, $className);

        $repositoryClassMapV1x = new RepositoryClassMap(ClientVersionMapInterface::VERSION_V1X);
        $className = $repositoryClassMapV1x->getClassName($class);
        $this->assertContains(ClientVersionMapInterface::VERSION_V1X, $className);

        $repositoryClassMap = new RepositoryClassMap();
        $className = $repositoryClassMap->getClassName($class);
        $this->assertContains(ClientVersionMapInterface::VERSION_V1X, $className);
    }

    public function testClassException()
    {
        $class = 'NotExistingClass';

        $repositoryClassMap = new RepositoryClassMap();

        try {
            $repositoryClassMap->getClassName($class);
        } catch(RepositoryClassMapException $exception) {
            $this->assertEquals('Class "' . $class  . '" is not defined', $exception->getMessage());
        }
    }

    public function testCreateDocumentRequest()
    {
        $class = 'CreateDocumentRequest';

        $this->versionTests($class);
    }

    public function testDeleteDocumentRequest()
    {
        $class = 'DeleteDocumentRequest';

        $this->versionTests($class);
    }

    public function testGetDocumentRequest()
    {
        $class = 'GetDocumentRequest';

        $this->versionTests($class);
    }

    private function versionTests($class)
    {
        //v90x
        $repositoryClassMapV090x = new RepositoryClassMap(ClientVersionMapInterface::VERSION_V090X);
        $className = $repositoryClassMapV090x->getClassName($class);
        $this->defaultInstanceByClassNameTest($className);

        //v1x
        $repositoryClassMapV1x = new RepositoryClassMap(ClientVersionMapInterface::VERSION_V1X);
        $className = $repositoryClassMapV1x->getClassName($class);
        $this->defaultInstanceByClassNameTest($className);

        //instance var version
        $repositoryClassMap = new RepositoryClassMap();
        $className = $repositoryClassMap->getClassName($class);
        $this->defaultInstanceByClassNameTest($className);
    }

    private function defaultInstanceByClassNameTest($className)
    {
        $instance = new $className('index', 'type', $this->serializer);
        $this->assertInstanceOf($className, $instance);
    }

}
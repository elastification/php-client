<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:43
 */

namespace Elastification\Client\Tests\Unit\Repository;

use Elastification\Client\Exception\RepositoryClassMapException;
use Elastification\Client\Repository\RepositoryClassMap;
use Elastification\Client\Repository\RepositoryClassMapInterface;
use Elastification\Client\Repository\RequestRepositoryFactory;
use Elastification\Client\Repository\RequestRepositoryFactoryInterface;
use Elastification\Client\Request\V1x\CreateDocumentRequest;

class RequestRepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var RequestRepositoryFactoryInterface
     */
    private $requestRepositoryFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder('Elastification\Client\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestRepositoryFactory = new RequestRepositoryFactory();
    }

    protected function tearDown()
    {
        $this->requestRepositoryFactory = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\Repository\RequestRepositoryFactoryInterface', $this->requestRepositoryFactory);
        $this->assertInstanceOf('Elastification\Client\Repository\RequestRepositoryFactory', $this->requestRepositoryFactory);
    }

    public function testVersionExceptionInConstructor()
    {
        $index = 'myIndex';
        $type = 'myType';
        $class = 'Elastification\Client\Request\V1x\CreateDocumentRequest';

        /** @var CreateDocumentRequest $instance */
        $instance = $this->requestRepositoryFactory->create($class, $index, $type, $this->serializer);

        $this->assertInstanceOf($class, $instance);
        $this->assertInstanceOf('Elastification\Client\Request\RequestInterface', $instance);
        $this->assertSame($instance->getIndex(), $index);
        $this->assertSame($instance->getType(), $type);
        $this->assertSame($instance->getSerializer(), $this->serializer);
    }

}
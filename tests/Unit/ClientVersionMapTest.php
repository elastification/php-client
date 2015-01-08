<?php
namespace Elastification\Client\Tests\Unit;

use Elastification\Client\ClientVersionMap;
use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Exception\ClientException;
use Elastification\Client\Exception\VersionException;

class ClientVersionMapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ClientVersionMapInterface
     */
    private $versionMap;

    protected function setUp()
    {
        parent::setUp();

        $this->versionMap = new ClientVersionMap();
    }

    protected function tearDown()
    {
        $this->versionMap = null;
        parent::tearDown();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Elastification\Client\ClientVersionMapInterface', $this->versionMap);
        $this->assertInstanceOf('Elastification\Client\ClientVersionMap', $this->versionMap);
    }

    public function testGetVersionV1x()
    {
        $this->assertSame(ClientVersionMapInterface::VERSION_V1X, $this->versionMap->getVersion('1.4'));
        $this->assertSame(ClientVersionMapInterface::VERSION_V1X, $this->versionMap->getVersion('1.4.1'));
        $this->assertSame(ClientVersionMapInterface::VERSION_V1X, $this->versionMap->getVersion('1.4.*'));
        $this->assertSame(ClientVersionMapInterface::VERSION_V1X, $this->versionMap->getVersion('1.7'));
    }

    public function testGetVersionV090x()
    {
        $this->assertSame(ClientVersionMapInterface::VERSION_V090X, $this->versionMap->getVersion('0.90'));
        $this->assertSame(ClientVersionMapInterface::VERSION_V090X, $this->versionMap->getVersion('0.90.13'));
        $this->assertSame(ClientVersionMapInterface::VERSION_V090X, $this->versionMap->getVersion('0.90.*'));
    }

    public function testGetVersionException()
    {
        try {
            $this->versionMap->getVersion('1');
        } catch(VersionException $exception) {
            $this->assertSame('Version not found', $exception->getMessage());
            return;
        }

        $this->fail('version exception not thrown');
    }

    public function testGetVersion2Exception()
    {
        try {
            $this->versionMap->getVersion('2.1');
        } catch(VersionException $exception) {
            $this->assertSame('Version not found', $exception->getMessage());
            return;
        }

        $this->fail('version exception not thrown');
    }

    public function testGetVersion080Exception()
    {
        try {
            $this->versionMap->getVersion('0.80');
        } catch(VersionException $exception) {
            $this->assertSame('Version not found', $exception->getMessage());
            return;
        }

        $this->fail('version exception not thrown');
    }

}


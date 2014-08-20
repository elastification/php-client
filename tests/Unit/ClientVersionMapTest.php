<?php
namespace Elastification\Client\Tests\Unit;

use Elastification\Client\ClientVersionMap;
use Elastification\Client\Exception\ClientException;

class ClientVersionMapTest extends \PHPUnit_Framework_TestCase
{

    public function testSuccessfulVersion()
    {
        $existingVersion = '0.90.x';
        $expectedReturn = 'V090x';
        $this->assertSame($expectedReturn, ClientVersionMap::getVersion($existingVersion));
    }

    public function testUnsuccessfulVersion()
    {
        $this->setExpectedException(
            'Elastification\Client\Exception\ClientException',
            'version 0.91.x is not defined'
        );
        ClientVersionMap::getVersion('0.91.x');
    }
}


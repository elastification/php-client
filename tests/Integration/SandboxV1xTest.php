<?php
namespace Elastification\Client\Tests\Integration;

/**
 * ${CARET}
 * @package Elastification\Client\Tests\Integration
 * @author Mario Mueller <mueller@freshcells.de>
 * @since 2014-08-13
 * @version 1.0.0
 */
class SandboxV1xTest extends \PHPUnit_Framework_TestCase
{

    const INDEX = 'dawen-elastic';
    const TYPE = 'sandbox';

    private $url = 'http://localhost:9200/';

    private $thriftClient;

    public function setUp()
    {

    }

    public function testThriftConnection()
    {
    }
}

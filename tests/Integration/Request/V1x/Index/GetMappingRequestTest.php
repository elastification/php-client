<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Index;


use Elastification\Client\Request\V1x\Index\GetMappingRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Tests\Integration\Request\V1x\AbstractElastic;

class GetMappingRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-get-mapping';

    public function testGetMappingWithType()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $getMappingRequest = new GetMappingRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        /** @var ResponseInterface $response */
        $response = $this->getClient()->send($getMappingRequest);

        $this->assertContains(self::TYPE, $response->getRawData());
        $this->assertContains('"properties":', $response->getRawData());
        $this->assertContains('"name":', $response->getRawData());
        $this->assertContains('"value":', $response->getRawData());
    }

    public function testGetMappingWithoutType()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $getMappingRequest = new GetMappingRequest(ES_INDEX, null, $this->getSerializer());

        /** @var ResponseInterface $response */
        $response = $this->getClient()->send($getMappingRequest);

        $this->assertContains(ES_INDEX, $response->getRawData());
        $this->assertContains(self::TYPE, $response->getRawData());
        $this->assertContains('"properties":', $response->getRawData());
        $this->assertContains('"name":', $response->getRawData());
        $this->assertContains('"value":', $response->getRawData());
    }
}

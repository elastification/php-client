<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\GetFieldMappingRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class GetFieldMappingRequestTest extends AbstractElastic
{
    const TYPE = 'request-index-get-field-mapping';

    public function testGetMappingWithType()
    {
        $this->createIndex();
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();

        $getFieldMappingRequest = new GetFieldMappingRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $getFieldMappingRequest->setField('name');

        /** @var ResponseInterface $response */
        $response = $this->getClient()->send($getFieldMappingRequest);

        $this->assertContains(self::TYPE, $response->getRawData());
        $this->assertContains('"name":', $response->getRawData());
        $data = $response->getData()->getGatewayValue();
        $this->assertArrayHasKey(ES_INDEX, $data);
        $this->assertArrayHasKey('mappings', $data[ES_INDEX]);
        $this->assertArrayHasKey(self::TYPE, $data[ES_INDEX]['mappings']);
        $this->assertArrayHasKey('name', $data[ES_INDEX]['mappings'][self::TYPE]);
        $this->assertArrayHasKey('full_name', $data[ES_INDEX]['mappings'][self::TYPE]['name']);
        $this->assertSame('name', $data[ES_INDEX]['mappings'][self::TYPE]['name']['full_name']);
        $this->assertArrayHasKey('mapping', $data[ES_INDEX]['mappings'][self::TYPE]['name']);
        $this->assertArrayHasKey('name', $data[ES_INDEX]['mappings'][self::TYPE]['name']['mapping']);
        $this->assertArrayHasKey('type', $data[ES_INDEX]['mappings'][self::TYPE]['name']['mapping']['name']);
        $this->assertSame('string', $data[ES_INDEX]['mappings'][self::TYPE]['name']['mapping']['name']['type']);
    }
}

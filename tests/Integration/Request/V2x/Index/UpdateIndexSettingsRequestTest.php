<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\IndexSettingsRequest;
use Elastification\Client\Request\V2x\Index\UpdateIndexSettingsRequest;
use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Response\V2x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class UpdateIndexSettingsRequestTest extends AbstractElastic
{
    const TYPE = 'request-update-index-settings';

    public function testIndexSettingsWithIndex()
    {
        $this->createIndex();
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $indexSettingsRequest = new IndexSettingsRequest(ES_INDEX, null, $this->getSerializer());

        /** @var ResponseInterface $response */
        $response = $this->getClient()->send($indexSettingsRequest);

        $data = $response->getData()->getGatewayValue();
        $this->assertArrayHasKey(ES_INDEX, $data);

        $this->assertEquals(1, $data[ES_INDEX]['settings']['index']['number_of_replicas']);

        $updateIndexSettingsRequest = new UpdateIndexSettingsRequest(ES_INDEX, null, $this->getSerializer());
        $updateIndexSettingsRequest->setBody(array(
            'index' => array('number_of_replicas' => 0)
        ));

        /** @var IndexResponse $updateResponse */
        $updateResponse = $this->getClient()->send($updateIndexSettingsRequest);

        $this->assertTrue($updateResponse->acknowledged());


        /** @var ResponseInterface $response */
        $response = $this->getClient()->send($indexSettingsRequest);

        $data = $response->getData()->getGatewayValue();
        $this->assertArrayHasKey(ES_INDEX, $data);

        $this->assertEquals(0, $data[ES_INDEX]['settings']['index']['number_of_replicas']);
    }

}

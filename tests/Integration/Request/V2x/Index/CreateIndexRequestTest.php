<?php
namespace Elastification\Client\Tests\Integration\Request\V2x\Index;


use Elastification\Client\Request\V2x\Index\CreateIndexRequest;
use Elastification\Client\Response\V2x\Index\IndexResponse;
use Elastification\Client\Tests\Integration\Request\V2x\AbstractElastic;

class CreateIndexRequestTest extends AbstractElastic
{

    public function testCreateIndex()
    {
        $this->assertFalse($this->hasIndex());

        $settings = array(
            'settings' => array(
                'index' => array(
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                )
            ),
            'mappings' => array(
                'test-type' => array(
                    '_source' => array('enabled' => false),
                    'properties' => array(
                        'test-field' => array(
                            'type' => 'string',
                            'index' => 'not_analyzed'
                        )
                    )
                )
            )
        );

        $createIndexRequest = new CreateIndexRequest(ES_INDEX, null, $this->getSerializer());
        $createIndexRequest->setBody($settings);

        /** @var IndexResponse $response */
        $response = $this->getClient()->send($createIndexRequest);

        $this->assertTrue($response->acknowledged());
        $this->assertTrue($this->hasIndex());
    }

}

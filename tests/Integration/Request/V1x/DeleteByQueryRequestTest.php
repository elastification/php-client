<?php
namespace Elastification\Client\Tests\Integration\Request\V1x;



use Elastification\Client\Request\V1x\CountRequest;
use Elastification\Client\Request\V1x\DeleteByQueryRequest;
use Elastification\Client\Response\V1x\CountResponse;

class DeleteByQueryRequestTest extends AbstractElastic
{

    const TYPE = 'request-delete-by-query';


    public function testDeleteSearch()
    {

        $this->createIndex();
        $data = array('name' => 'test', 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'test', 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $data = array('name' => 'mega', 'value' => 'myTestVal' . rand(100, 10000));
        $this->createDocument(self::TYPE, $data);
        $this->refreshIndex();


        $countRequest = new CountRequest(ES_INDEX, self::TYPE, $this->getSerializer());

        /** @var CountResponse $response */
        $response = $this->getClient()->send($countRequest);
        $this->assertSame(3, $response->getCount());

        $deleteSearchRequest = new DeleteByQueryRequest(ES_INDEX, self::TYPE, $this->getSerializer());
        $deleteSearchRequest->setBody(array('query' => array('term' => array('name' => 'test'))));
        $response = $this->getClient()->send($deleteSearchRequest);

        $this->refreshIndex();

        $this->assertContains('_indices', $response->getRawData());
        $this->assertContains(ES_INDEX, $response->getRawData());

        $response = $this->getClient()->send($countRequest);
        $this->assertSame(1, $response->getCount());
    }
}

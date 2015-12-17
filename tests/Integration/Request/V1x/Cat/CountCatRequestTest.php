<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\CountCatRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\Shared\Cat\AbstractCountCatResponse;
use Elastification\Client\Response\V1x\Cat\CountCatResponse;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class AliasesCatRequestTest extends AbstractElastic
{
    const TYPE = 'request-cat-count';

    public function testCountCat()
    {

        $countCatRequest = new CountCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];
        $this->assertCount(3, $index);

        $this->assertArrayHasKey('epoch', $index);
        $this->assertArrayHasKey('timestamp', $index);
        $this->assertArrayHasKey('count', $index);

        $this->assertEquals(0, $index['count']);
    }

    public function testCountCatWithDocuments()
    {
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $countCatRequest = new CountCatRequest(null, null, $this->getSerializer());
        /** @var Response $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data->getGatewayValue()[0];
        $this->assertCount(3, $index);

        $this->assertArrayHasKey('epoch', $index);
        $this->assertArrayHasKey('timestamp', $index);
        $this->assertArrayHasKey('count', $index);

        $this->assertEquals(3, $index['count']);
    }

}

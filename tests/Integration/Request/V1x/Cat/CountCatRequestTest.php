<?php
namespace Elastification\Client\Tests\Integration\Request\V1x\Cat;


use Elastification\Client\Request\V1x\Cat\CountCatRequest;
use Elastification\Client\Response\Shared\Cat\AbstractCountCatResponse;
use Elastification\Client\Response\V1x\Cat\CountCatResponse;
use Elastification\Client\Tests\Integration\Repository\V1x\AbstractElastic;

class AliasesCatRequestTest extends AbstractElastic
{
    const TYPE = 'request-cat-count';

    public function testAliasesCat()
    {

        $countCatRequest = new CountCatRequest(null, null, $this->getSerializer());
        /** @var CountCatResponse $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data[0];
        $this->assertCount(3, $index);

        $this->assertArrayHasKey(AbstractCountCatResponse::PROP_EPOCH, $index);
        $this->assertArrayHasKey(AbstractCountCatResponse::PROP_TIMESTAMP, $index);
        $this->assertArrayHasKey(AbstractCountCatResponse::PROP_COUNT, $index);

        $this->assertEquals(0, $index[AbstractCountCatResponse::PROP_COUNT]);
    }

    public function testAliasesCatWithDocuments()
    {
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->createDocument(self::TYPE);
        $this->refreshIndex();

        $countCatRequest = new CountCatRequest(null, null, $this->getSerializer());
        /** @var CountCatResponse $response */
        $response = $this->getClient()->send($countCatRequest);

        $data = $response->getData();
        $this->assertCount(1, $data);

        $index = $data[0];
        $this->assertCount(3, $index);

        $this->assertArrayHasKey(AbstractCountCatResponse::PROP_EPOCH, $index);
        $this->assertArrayHasKey(AbstractCountCatResponse::PROP_TIMESTAMP, $index);
        $this->assertArrayHasKey(AbstractCountCatResponse::PROP_COUNT, $index);

        $this->assertEquals(3, $index[AbstractCountCatResponse::PROP_COUNT]);
    }

}

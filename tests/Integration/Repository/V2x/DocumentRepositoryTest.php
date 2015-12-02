<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 18/12/14
 * Time: 11:47
 */

namespace Elastification\Client\Tests\Integration\Repository\V2x;

use Elastification\Client\ClientVersionMap;
use Elastification\Client\Repository\DocumentRepository;
use Elastification\Client\Repository\DocumentRepositoryInterface;
use Elastification\Client\Response\V2x\CreateUpdateDocumentResponse;
use Elastification\Client\Response\V2x\DocumentResponse;
use Elastification\Client\Exception\ClientException;

class DocumentRepositoryTest extends AbstractElastic
{
    const TYPE = 'repository-document';

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->documentRepository = new DocumentRepository(
            $this->getClient(),
            $this->getSerializer(),
            null,
            ClientVersionMap::VERSION_V2X);
    }

    protected function tearDown()
    {
        $this->documentRepository = null;

        parent::tearDown();
    }


    public function testCreateDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        /** @var DocumentResponse $response */
        $response = $this->documentRepository->create(ES_INDEX, self::TYPE, $data);

        $this->assertSame(ES_INDEX, $response->getIndex());
        $this->assertSame(self::TYPE, $response->getType());
        $this->assertSame(1, $response->getVersion());
        $this->assertTrue($response->getData()['created']);
        $this->assertTrue(strlen($response->getId()) > 5);
    }

    public function testGetDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        /** @var CreateUpdateDocumentResponse $createResponse */
        $createResponse = $this->documentRepository->create(ES_INDEX, self::TYPE, $data);
        /** @var DocumentResponse $getResponse */
        $getResponse = $this->documentRepository->get(ES_INDEX, self::TYPE, $createResponse->getId());

        $this->assertTrue($getResponse->found());
        $this->assertSame($createResponse->getId(), $getResponse->getId());
        $this->assertSame(1, $getResponse->getVersion());
        $this->assertSame(ES_INDEX, $getResponse->getIndex());
        $this->assertSame(self::TYPE, $getResponse->getType());
        $this->assertSame($data['name'], $getResponse->getSource()['name']);
        $this->assertSame($data['value'], $getResponse->getSource()['value']);
    }

    public function testDeleteDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        /** @var CreateUpdateDocumentResponse $createResponse */
        $createResponse = $this->documentRepository->create(ES_INDEX, self::TYPE, $data);
        $this->refreshIndex();

        $this->documentRepository->delete(ES_INDEX, self::TYPE, $createResponse->getId());

        try {
            $this->documentRepository->get(ES_INDEX, self::TYPE, $createResponse->getId());
        } catch (ClientException $exception) {
            $this->assertSame(404, $exception->getCode());
            $this->assertContains('404', $exception->getMessage());

            return;
        }

        $this->fail();
    }

    public function testUpdateDocument()
    {
        $data = array('name' => 'test' . rand(100, 10000), 'value' => 'myTestVal' . rand(100, 10000));

        /** @var CreateUpdateDocumentResponse $createResponse */
        $createResponse = $this->documentRepository->create(ES_INDEX, self::TYPE, $data);
        $this->refreshIndex();

        $data['name'] = 'test3';
        $this->documentRepository->update(ES_INDEX, self::TYPE, $createResponse->getId(), $data);

        /** @var DocumentResponse $getResponse */
        $getResponse = $this->documentRepository->get(ES_INDEX, self::TYPE, $createResponse->getId());
        $storedData = $getResponse->getSource();
        $this->assertSame($data, $storedData);
    }

}


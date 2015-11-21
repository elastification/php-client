<?php
namespace Elastification\Client\Tests\Integration\Request\V1x;



use Elastification\Client\Exception\ClientException;
use Elastification\Client\Request\V1x\CreateTemplateRequest;
use Elastification\Client\Request\V1x\DeleteTemplateRequest;
use Elastification\Client\Request\V1x\GetTemplateRequest;
use Elastification\Client\Response\Response;
use Elastification\Client\Response\V1x\Index\IndexResponse;

class CreateDeleteGetTemplateRequestTest extends AbstractElastic
{

    public function testCreateDeleteTemplate()
    {
        $templateName = 'test-template';
        $template = [
            'template' => "te*",
            'settings' => [
                "number_of_shards" => 1
            ],
            'mappings' => [
                'type1' => [
                    '_source' => [ "enabled" => false ]
                ]
            ]
        ];


        $createTemplateRequest = new CreateTemplateRequest($templateName, $this->getSerializer());
        $createTemplateRequest->setBody($template);

        /** @var IndexResponse $createResponse */
        $createResponse = $this->getClient()->send($createTemplateRequest);
        $this->assertTrue($createResponse->acknowledged());


        $getTemplateRequest = new GetTemplateRequest($templateName, $this->getSerializer());
        /** @var Response $getResponse */
        $getResponse = $this->getClient()->send($getTemplateRequest);
        $templates = $getResponse->getData()->getGatewayValue();
        $this->assertArrayHasKey($templateName, $templates);

        $this->assertEquals($template['template'], $templates[$templateName]['template']);
        $this->assertEquals($template['mappings'], $templates[$templateName]['mappings']);

        $deleteTemplateRequest = new DeleteTemplateRequest($templateName, $this->getSerializer());
        /** @var IndexResponse $deleteResponse */
        $deleteResponse = $this->getClient()->send($deleteTemplateRequest);
        $this->assertTrue($deleteResponse->acknowledged());

        $getTemplateRequest = new GetTemplateRequest($templateName, $this->getSerializer());

        try {
            /** @var Response $getResponse */
            $this->getClient()->send($getTemplateRequest);
        } catch(ClientException $exception) {
            $this->assertSame(404, $exception->getCode());
            return;
        }

        $this->fail();
    }
}

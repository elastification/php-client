<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace Elastification\Client\Serializer;

use Elastification\Client\Serializer\Exception\DeserializationFailureException;
use Elastification\Client\Serializer\Gateway\GatewayInterface;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;
use Elastification\Client\Serializer\JmsSerializer\SourceSubscribingHandler;
use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

/**
 * JMS Serializer.
 *
 * In order to use this DeSer mechanism you need to register a custom handler
 * at the build time of your Serializer instance. As this cannot be influenced
 * after the building process has finished, you need to do it yourself.
 *
 * Please have a look at the handler here {@see
 * Elastification\Client\Serializer\JmsSerializer\SourceSubscribingHandler}
 * and please have a look at the test here {@see Elastification\Client\Tests\Unit\Serializer\JmsSerializerTest}, esp.
 * the setUp method tells you how to register this handler. You can pass the class to use for _source serialization
 * into the constructor of the handler class.
 *
 * @package Elastification\Client\Serializer
 * @author  Mario Mueller
 */
class JmsSerializer implements SerializerInterface
{
    /**
     * @var string
     */
    const SERIALIZER_FORMAT = 'json';

    /**
     * This class is used to deserialize the response.
     * This entity should only care about the elasticsearch part of the
     * response, not about the source part or the results underneath hits->hits.
     *
     * @var string
     */
    private $deserializerClass = 'Elastification\Client\Serializer\JmsSerializer\SearchResponseEntity';

    /**
     * @var Serializer
     */
    private $jms;

    /**
     * @var SerializationContext
     */
    private $jmsSerializeContext;

    /**
     * @var DeserializationContext
     */
    private $jmsDeserializeContext;
    /**
     *
     * @var array
     */
    private $indexTypeClassMap;
    /**
     *
     * @var SourceSubscribingHandler
     */
    private $handler;

    /**
     * @param Serializer               $jms
     * @param SourceSubscribingHandler $handler This must be the same instance as the one you used for the init of the Serializer
     * @param array                    $indexTypeClassMap Represents an array of ['indexName' => ['typeName' => 'SourceClassName']]
     */
    function __construct(Serializer $jms, SourceSubscribingHandler $handler, array $indexTypeClassMap)
    {
        $this->jms = $jms;
        $this->indexTypeClassMap = $indexTypeClassMap;
        $this->handler = $handler;
    }

    /**
     * Serializes given data to string
     *
     * @param mixed $data
     * @param array $params
     *
     * @return string
     */
    public function serialize($data, array $params = array())
    {
        return $this->jms->serialize(
            $data,
            self::SERIALIZER_FORMAT,
            $this->determineContext($this->jmsSerializeContext, $params)
        );
    }

    /**
     * Deserializes given data to array or object
     *
     * @param string $data
     * @param array  $params
     *
     * @return GatewayInterface
     */
    public function deserialize($data, array $params = array())
    {
        $sourceClass = $this->getSourceClassFromMapping($params);
        $this->handler->setSourceDeSerClass($sourceClass);
        return new NativeObjectGateway(
            $this->jms->deserialize(
                $data,
                $this->deserializerClass,
                self::SERIALIZER_FORMAT,
                $this->determineContext($this->jmsDeserializeContext, $params)
            )
        );
    }

    /**
     * gets the source class.
     *
     * @param array $params
     * @return string
     * @author Daniel Wendlandt
     */
    private function getSourceClassFromMapping(array $params)
    {
        $index = $params['index'];
        $type = $params['type'];
        if (!isset($this->indexTypeClassMap[$index])) {
            throw new DeserializationFailureException('Cannot find index in source class map: ' . $index);
        }

        if (!isset($this->indexTypeClassMap[$index][$type])) {
            throw new DeserializationFailureException(
                'Cannot find type in source class map: ' . $type . ' in index ' . $index
            );
        }
        return $this->indexTypeClassMap[$index][$type];
    }

    /**
     * Simple rule: Use internal if present but let params override any internal.
     *
     * @param Context $internalProperty
     * @param array   $params
     *
     * @return Context|null
     * @author Mario Mueller
     */
    private function determineContext($internalProperty, array $params)
    {
        $ctx = null;
        if ($internalProperty != null && !isset($params['ctx'])) {
            // We need to clone it as jms contexts are not reusable.
            $ctx = clone $internalProperty;
        }

        // When a passed context exists, override the internal one
        if (isset($params['ctx'])) {
            $ctx = $params['ctx'];
        }

        return $ctx;
    }

    /**
     * @return SerializationContext
     * @author Mario Mueller (autogenerated code)
     */
    public function getJmsSerializeContext()
    {
        return $this->jmsSerializeContext;
    }

    /**
     * @param SerializationContext $jmsContext
     *
     * @return void
     * @author Mario Mueller (autogenerated code)
     */
    public function setJmsSerializeContext(SerializationContext $jmsContext)
    {
        $this->jmsSerializeContext = $jmsContext;
    }

    /**
     * @return DeserializationContext
     * @author Mario Mueller (autogenerated code)
     */
    public function getJmsDeserializeContext()
    {
        return $this->jmsDeserializeContext;
    }

    /**
     * @param DeserializationContext $jmsDeserializeContext
     *
     * @return void
     * @author Mario Mueller (autogenerated code)
     */
    public function setJmsDeserializeContext($jmsDeserializeContext)
    {
        $this->jmsDeserializeContext = $jmsDeserializeContext;
    }

    /**
     * @return string
     * @author Mario Mueller (autogenerated code)
     */
    public function getDeserializerClass()
    {
        return $this->deserializerClass;
    }

    /**
     * @param string $deserializerClass
     *
     * @return void
     * @author Mario Mueller (autogenerated code)
     */
    public function setDeserializerClass($deserializerClass)
    {
        $this->deserializerClass = $deserializerClass;
    }
}

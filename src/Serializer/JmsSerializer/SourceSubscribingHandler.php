<?php
namespace Elastification\Client\Serializer\JmsSerializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

/**
 * @package Elastification\Client\Serializer\JmsSerializer
 * @author Mario Mueller
 * @since 2014-08-16
 * @version 1.0.0
 */
class SourceSubscribingHandler implements SubscribingHandlerInterface
{
    /**
     * @var string
     */
    private $sourceDeSerClass;

    /**
     * @param string $sourceDeSerClass
     */
    function __construct($sourceDeSerClass)
    {
        $this->sourceDeSerClass = $sourceDeSerClass;
    }

    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'ElastificationSource',
                'method' => 'serializeElastificationSource',
            ),
        );
    }


    public function serializeElastificationSource(
        JsonDeserializationVisitor $visitor,
        $data,
        array $type,
        Context $context
    ) {
        return $visitor->getNavigator()->accept($data, ['name' => $this->sourceDeSerClass], $context);
    }
}

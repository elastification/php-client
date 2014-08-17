<?php
namespace Elastification\Client\Serializer\JmsSerializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;

/**
 * This handler enables us to dynamically inject a deserialization class for the _source element of the
 * elasticsearch response.
 *
 * @package Elastification\Client\Serializer\JmsSerializer
 * @author  Mario Mueller
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

    /**
     * @param JsonDeserializationVisitor $visitor
     * @param                            $data
     * @param array                      $type
     * @param Context                    $context
     *
     * @return mixed
     * @author Mario Mueller
     */
    public function serializeElastificationSource(
        JsonDeserializationVisitor $visitor,
        $data,
        array $type,
        Context $context
    ) {
        $this->sourceDeSerClass = empty($this->sourceDeSerClass) ? $type['name'] : $this->sourceDeSerClass;
        return $visitor->getNavigator()->accept($data, ['name' => $this->sourceDeSerClass], $context);
    }
}

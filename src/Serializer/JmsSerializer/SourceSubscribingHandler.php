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

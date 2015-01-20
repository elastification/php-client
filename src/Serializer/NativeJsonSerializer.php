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
use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\Gateway\NativeObjectGateway;

/**
 * Simple Native JSON Serializer.
 *
 * Serializes the response using the native json_encode/json_decode commands. the params allow you to control
 * the type of result, either a stdClass or an array/hash map.
 *
 * @package Elastification\Client\Serializer
 * @author  Daniel Wendlandt
 */
class NativeJsonSerializer implements SerializerInterface
{

    /**
     * @inheritdoc
     */
    public function serialize($data, array $params = array())
    {
        $forceObject = $this->forceObject($params);

        return json_encode($data, $forceObject);
    }

    /**
     * @inheritdoc
     */
    public function deserialize($data, array $params = array())
    {
        $assoc = $this->useAssoc($params);
        $decodedJson = json_decode($data, $assoc);

        if ($decodedJson != null) {
            return $this->createGateway($assoc, $decodedJson);
        }

        // json_last_error_msg is only present in 5.5 and higher.
        if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            throw new DeserializationFailureException(json_last_error_msg(), json_last_error());
        }

        // Fall through for versions below 5.5
        throw new DeserializationFailureException('JSON syntax error in: ' . $data);
    }

    /**
     * Decides whether to use assoc or stdClass.
     *
     * @param array $params The array of params.
     *
     * @return boolean
     * @author Mario Mueller
     */
    private function useAssoc($params)
    {
        $assoc = true;
        if (isset($params['assoc']) && is_bool($params['assoc'])) {
            $assoc = $params['assoc'];
        }
        return $assoc;
    }

    /**
     * Decides whether to force objects.
     *
     * @param array $params The array of params.
     *
     * @return integer
     * @author Patrick Pokatilo <mail@shyxormz.net>
     */
    private function forceObject($params)
    {
        $forceObject = false;
        if (isset($params['force_object']) && is_bool($params['force_object']) && true === $params['force_object']) {
            $forceObject = JSON_FORCE_OBJECT;
        }
        return $forceObject;
    }

    /**
     * Creates the correct gateway based on assoc being true or false.
     *
     * The information if we need assoc could also be determined by looking
     * at the type of $decodedJson, but we thing a boolean is faster and the
     * decision has already been made before.
     *
     * @author Mario Mueller
     *
     * @param boolean         $assoc Should we use assoc?
     * @param array|\stdClass $decodedJson
     *
     * @return \Elastification\Client\Serializer\Gateway\GatewayInterface
     */
    private function createGateway($assoc, $decodedJson)
    {
        if ($assoc === true) {
            $instance = new NativeArrayGateway($decodedJson);
        } else {
            $instance = new NativeObjectGateway($decodedJson);
        }
        return $instance;
    }
}

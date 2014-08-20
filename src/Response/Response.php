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
namespace Elastification\Client\Response;

use Elastification\Client\Serializer\Gateway\NativeArrayGateway;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class Response
 *
 * @package Elastification\Client\Response
 * @author  Daniel Wendlandt
 */
class Response implements ResponseInterface
{

    /**
     * @var string
     */
    private $rawData;

    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     */
    private $serializerParams;

    /**
     * @param                     $rawData
     * @param SerializerInterface $serializer
     * @param array               $serializerParams
     */
    public function __construct($rawData, SerializerInterface $serializer, array $serializerParams = array())
    {
        $this->rawData = $rawData;
        $this->serializer = $serializer;
        $this->serializerParams = $serializerParams;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $this->processData();

        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @inheritdoc
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * checks if data is already deserialized.
     *
     * @author Daniel Wendlandt
     */
    protected function processData()
    {
        if (null === $this->data) {
            if (null !== $this->rawData) {
                $this->data = $this->serializer->deserialize($this->rawData, $this->serializerParams);
            }
        }
    }

    /**
     * gets a property of the data object/array
     *
     * @param string $property
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    protected function get($property)
    {
        $val = $this->data[$property];
        if (is_scalar($val)) {
            return $val;
        } else {
            return $val->getGatewayValue();
        }
    }

    /**
     * checks if a property of data array/object exists
     *
     * @param string $property
     *
     * @return bool
     * @author Daniel Wendlandt
     */
    protected function has($property)
    {
        return isset($this->data[$property]);
    }
}

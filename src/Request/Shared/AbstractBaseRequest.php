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
namespace Elastification\Client\Request\Shared;

use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class AbstractBaseRequest
 *
 * @package Elastification\Client\Request\Shared
 * @author  Daniel Wendlandt
 */
abstract class AbstractBaseRequest implements RequestInterface
{
    /**
     * @var \Elastification\Client\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $serializerParams = array();

    /**
     * @var null|string
     */
    protected $index = null;

    /**
     * @var null|string
     */
    protected $type = null;

    /**
     * @param string                                                $index
     * @param string                                                $type
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array                                                 $serializerParams
     *
     * @author Daniel Wendlandt
     */
    public function __construct($index, $type, SerializerInterface $serializer, array $serializerParams = array())
    {
        $this->serializer = $serializer;
        $this->serializerParams = $serializerParams;

        if (!empty($index)) {
            $this->index = $index;
        }

        if (!empty($type)) {
            $this->type = $type;
        }
    }

    /**
     * @inheritdoc
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @inheritdoc
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @inheritdoc
     */
    public function getSerializerParams()
    {
        return $this->serializerParams;
    }

}

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
namespace Elastification\Client\Request;

use Elastification\Client\Response\ResponseInterface;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Interface RequestInterface
 *
 * @package Elastification\Client\Request
 * @author  Daniel Wendlandt
 */
interface RequestInterface
{
    /**
     * Gets the elasticsearch index
     *
     * @return null|string
     * @author Daniel Wendlandt
     */
    public function getIndex();

    /**
     * Gets the elasticsearch type
     *
     * @return null|string
     * @author Daniel Wendlandt
     */
    public function getType();

    /**
     * Gets the request method
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getMethod();

    /**
     * Gets the elasticsearch action/endpoint like
     * (_search, _mapping)
     *
     * @return null|string
     * @author Daniel Wendlandt
     */
    public function getAction();

    /**
     * Gets the serializer
     *
     * @return SerializerInterface
     * @author Daniel Wendlandt
     */
    public function getSerializer();

    /**
     * Gets the serializer params
     *
     * @return array
     * @author Daniel Wendlandt
     */
    public function getSerializerParams();

    /**
     * get the body
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getBody();

    /**
     * before setting data it should be serialized
     *
     * @param mixed $body
     *
     * @return void
     * @throws \Elastification\Client\Exception\RequestException
     * @author Daniel Wendlandt
     */
    public function setBody($body);

    /**
     * @param string                                                $rawData
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array                                                 $serializerParams
     *
     * @return null|ResponseInterface
     * @author Daniel Wendlandt
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array()
    );

    /**
     * gets a response class name that is supported by this class
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getSupportedClass();

}

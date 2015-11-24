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
namespace Elastification\Client\Request\V1x;

use Elastification\Client\Request\Shared\AbstractNodeInfoRequest;
use Elastification\Client\Response\V1x\NodeInfoResponse;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class NodeInfoRequest
 *
 * @package Elastification\Client\Request\V1x
 * @author  Daniel Wendlandt
 */
class NodeInfoRequest extends AbstractNodeInfoRequest
{

    /**
     * @param string                                                $rawData
     * @param \Elastification\Client\Serializer\SerializerInterface $serializer
     * @param array                                                 $serializerParams
     *
     * @return NodeInfoResponse
     * @author Daniel Wendlandt
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array()
    ) {

        return new NodeInfoResponse($rawData, $serializer, $serializerParams);
    }

    /**
     * gets a response class name that is supported by this class
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getSupportedClass()
    {
        return 'Elastification\Client\Response\V1x\NodeInfoResponse';
    }
}

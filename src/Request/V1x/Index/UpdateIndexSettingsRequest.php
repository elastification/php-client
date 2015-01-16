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

namespace Elastification\Client\Request\V1x\Index;

use Elastification\Client\Request\Shared\Index\AbstractUpdateIndexSettingsRequest;
use Elastification\Client\Response\V1x\Index\IndexResponse;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class UpdateIndexSettingsRequest
 *
 * @package Elastification\Client\Request\V1x\Index
 * @author Patrick Pokatilo <mail@shyxormz.net>
 */
class UpdateIndexSettingsRequest extends AbstractUpdateIndexSettingsRequest
{
    /**
     * @param string $rawData
     * @param SerializerInterface $serializer
     * @param array $serializerParams
     * @return IndexResponse
     * @author Daniel Wendlandt
     * @author Patrick Pokatilo <mail@shyxormz.net>
     */
    public function createResponse(
        $rawData,
        SerializerInterface $serializer,
        array $serializerParams = array())
    {

        return new IndexResponse($rawData, $serializer, $serializerParams);
    }

    /**
     * Gets a response class name that is supported by this class
     *
     * @return string
     * @author Daniel Wendlandt
     * @author Patrick Pokatilo <mail@shyxormz.net>
     */
    public function getSupportedClass()
    {
        return IndexResponse::class;
    }
}

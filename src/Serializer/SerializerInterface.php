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

use Elastification\Client\Serializer\Gateway\GatewayInterface;

/**
 * Interface SerializerInterface
 *
 * @package Elastification\Client\Serializer
 * @author  Daniel Wendlandt
 */
interface SerializerInterface
{
    /**
     * Serializes given data to string.
     *
     * @param mixed $data   The data to handle.
     * @param array $params The params are passed to the chosen serializer.
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function serialize($data, array $params = array());

    /**
     * Deserializes given data to array or object.
     *
     * The response will be a {@see GatewayInterface}, which enables you to use any response
     * like an array, because the gateway interface extends from {@see \ArrayAccess}. It must also
     * implement {@see \Countable} and {@see \Iteratable}.
     *
     * The gateway will provide a method to get the original value behind the gateway. This is what
     * you want if you need to work with the real result.
     *
     * @param string $data   The data to handle.
     * @param array  $params The params are passed to the chosen serializer.
     *
     * @return GatewayInterface
     * @author Daniel Wendlandt
     */
    public function deserialize($data, array $params = array());
}

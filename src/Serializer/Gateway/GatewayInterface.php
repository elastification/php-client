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
namespace Elastification\Client\Serializer\Gateway;

/**
 * Interface for accessing properties of the serialized data.
 *
 * The idea behind the gateway is to create a simple abstraction for the response
 * layer to access things like the response time ("took") and to get to know if the request went well, etc.
 *
 * The gateway is not meant to be used outside of the ecosystem of the client. Use the
 * GatewayInterface#getGatewayValue method to get the real, un-managed
 * result of your chosen serializer.
 *
 * For performance reasons you should call this method as soon as possible in your code and work with the real result.
 *
 * @see    Elastification\Client\Serializer\Gateway\GatewayInterface#getGatewayValue
 * @author Mario Mueller
 */
interface GatewayInterface extends \ArrayAccess, \Iterator, \Countable
{
    /**
     * Returns the original value.
     *
     * @return mixed
     * @author Mario Mueller
     */
    public function getGatewayValue();
}

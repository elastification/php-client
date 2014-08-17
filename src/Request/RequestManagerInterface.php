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

use Elastification\Client\Exception\RequestManagerException;

/**
 * Interface RequestManagerInterface
 *
 * @package Elastification\Client\Request
 * @author  Daniel Wendlandt
 */
interface RequestManagerInterface
{
    /**
     * Gets a defined request.
     *
     * @param string $name
     *
     * @return null | RequestInterface
     * @author Daniel Wendlandt
     */
    public function getRequest($name);

    /**
     * Checks if a reguest is registered for a name
     *
     * @param $name
     *
     * @return boolean
     * @author Daniel Wendlandt
     */
    public function hasRequest($name);

    /**
     * removes a request by name.
     * Returns bool for removing an existing request or not
     *
     * @param string $name
     *
     * @return boolean
     * @author Daniel Wendlandt
     */
    public function removeRequest($name);

    /**
     * deletes all requests.
     *
     * @return void
     * @author Daniel Wendlandt
     */
    public function reset();

    /**
     * Sets/registers a request by name
     *
     * @param string           $name
     * @param RequestInterface $request
     *
     * @return void
     * @throws RequestManagerException
     * @author Daniel Wendlandt
     */
    public function setRequest($name, RequestInterface $request);

}

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
 * Class RequestManager
 *
 * @package Elastification\Client\Request
 * @author  Daniel Wendlandt
 */
class RequestManager implements RequestManagerInterface
{

    /**
     * @var array
     */
    private $requests = array();

    /**
     * @inheritdoc
     */
    public function getRequest($name)
    {
        if ($this->requestExists($name)) {
            return $this->requests[$name];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function hasRequest($name)
    {
        return $this->requestExists($name);
    }

    /**
     * @inheritdoc
     */
    public function removeRequest($name)
    {
        if ($this->requestExists($name)) {
            unset($this->requests[$name]);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        $this->requests = array();
    }

    /**
     * @inheritdoc
     */
    public function setRequest($name, RequestInterface $request)
    {
        if ($this->requestExists($name)) {
            throw new RequestManagerException('a request for "' . $name . '" is already registered');
        }

        $this->requests[$name] = $request;
    }

    /**
     * checks if a request is registered for given name
     *
     * @param string $name
     *
     * @return bool
     * @author Daniel Wendlandt
     */
    private function requestExists($name)
    {
        return isset($this->requests[$name]);
    }
}

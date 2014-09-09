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

namespace Elastification\Client\Request\Shared\Index;

use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\AbstractBaseRequest;

/**
 * Class AbstractDeleteMappingRequest
 *
 * @package Elastification\Client\Request\Shared\Index
 * @author Daniel Wendlandt
 */
abstract class AbstractDeleteWarmerRequest extends AbstractBaseRequest
{

    const REQUEST_ACTION = '_warmer';

    /**
     * @var null|string
     */
    private $warmerName = null;

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::DELETE;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        if(empty($this->warmerName)) {
            throw new RequestException('Warmer name is not set');
        }

        return self::REQUEST_ACTION . '/' . $this->warmerName;
    }

    /**
     * Gets the name of the warmer
     *
     * @return null|string
     * @author Daniel Wendlandt
     */
    public function getWarmerName()
    {
        return $this->warmerName;
    }

    /**
     * sets the name of the warmer
     *
     * @param string $warmerName
     * @author Daniel Wendlandt
     */
    public function setWarmerName($warmerName)
    {
        $this->warmerName = $warmerName;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        //do nothing
    }

}
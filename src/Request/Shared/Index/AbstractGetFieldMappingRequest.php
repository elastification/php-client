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

use Elastification\Client\ClientInterface;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\AbstractBaseRequest;

abstract class AbstractGetFieldMappingRequest extends AbstractBaseRequest
{

    const REQUEST_ACTION = '_mapping';

    private $field = null;

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::GET;
    }

    /**
     * getter for type. A short fake.
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getType()
    {
        return self::REQUEST_ACTION;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return $this->type . ClientInterface::PATH_DIVIDER . $this->field;
    }

    /**
     * get the body
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getBody()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param null $field
     */
    public function setField($field)
    {
        $this->field = 'field' . ClientInterface::PATH_DIVIDER . $field;
    }

    /**
     * before setting data it should be serialized
     *
     * @param mixed $body
     *
     * @throws \Elastification\Client\Exception\RequestException
     * @author Daniel Wendlandt
     */
    public function setBody($body)
    {
        // do nothing here
    }

}

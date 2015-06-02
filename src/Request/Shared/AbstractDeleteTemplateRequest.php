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

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Serializer\SerializerInterface;

/**
 * Class AbstractDeleteTemplateRequest
 *
 * @package Elastification\Client\Request\Shared\Index
 * @author  Daniel Wendlandt
 */
abstract class AbstractDeleteTemplateRequest extends AbstractBaseRequest
{

    const REQUEST_ACTION = '_template';

    /**
     * @param string              $templateName
     * @param SerializerInterface $serializer
     * @param array               $serializerParams
     */
    public function __construct($templateName, SerializerInterface $serializer, array $serializerParams = array())
    {
        $this->serializer = $serializer;
        $this->serializerParams = $serializerParams;

        if (!empty($templateName)) {
            $this->type = $templateName;
        }
    }

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
    public function getIndex()
    {
        return self::REQUEST_ACTION;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return null;
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

    /**
     * Sets the template name
     *
     * @param $name
     *
     * @author Daniel Wendlandt
     */
    public function setTemplateName($name)
    {
        $this->type = $name;
    }
}

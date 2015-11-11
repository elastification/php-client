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

namespace Elastification\Client\Request\Shared\Bulk;

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\AbstractBaseRequest;

/**
 * Class AbstractCreateIndexRequest
 *
 * @package Elastification\Client\Request\Shared\Index
 * @author  Daniel Wendlandt
 */
abstract class AbstractBulkIndexRequest extends AbstractBaseRequest
{
    const LINE_BREAK = "\n";
    const REQUEST_ACTION = '_bulk';
    const BULK_ACTION = 'index';

    /**
     * @var null|mixed
     */
    private $body = null;

    /**
     * This will overwrite getIndex method for returning null.
     * The index property is only for internal use.
     *
     * @return null
     */
    public function getIndex()
    {
        return null;
    }

    /**
     * This will overwrite getType method for returning null.
     * The type property is only for internal use.
     *
     * @return null
     */
    public function getType()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::POST;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return self::REQUEST_ACTION;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        //to nothing
    }

    /**
     * adds a document to the body and transforms it in the right format.
     *
     * @param array|object $doc
     * @param string $id
     * @author Daniel Wendlandt
     */
    public function addDocument($doc, $id = null)
    {
        $action = array(
            self::BULK_ACTION => array(
                '_id' => $id,
                '_index' => $this->index,
                '_type' => $this->type
            )
        );

        $this->body .= json_encode($action) . self::LINE_BREAK;
        $this->body .= $this->serializer->serialize($doc, $this->serializerParams) . self::LINE_BREAK;
    }
}

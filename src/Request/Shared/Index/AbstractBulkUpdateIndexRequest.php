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

use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Request\Shared\AbstractBaseRequest;

/**
 * Class AbstractCreateIndexRequest
 *
 * @package Elastification\Client\Request\Shared\Index
 * @author  Daniel Wendlandt
 */
abstract class AbstractBulkUpdateIndexRequest extends AbstractBaseRequest
{

    /**
     * @var null|mixed
     */
    private $body = null;

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::PUT;
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
     * @param string $id
     * @param array $doc
     * @author Daniel Wendlandt
     */
    public function addDocument($id, array $doc)
    {
        $update = array(
            'update' => array(
                '_id' => $id,
                '_index' => $this->index,
                '_type' => $this->type
            )
        );

        if(null !== $this->body) {
            $this->body .= PHP_EOL;
        }

        $this->body .= json_encode($update) . PHP_EOL;
        $this->body .= '{"doc": ' . $this->serializer->serialize($doc, $this->serializerParams) . '}';
    }
}

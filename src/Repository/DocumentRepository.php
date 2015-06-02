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

namespace Elastification\Client\Repository;

class DocumentRepository extends AbstractRepository implements DocumentRepositoryInterface
{

    /**
     * creates a document
     *
     * @param string $index
     * @param string $type
     * @param mixed  $document
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function create($index, $type, $document)
    {
        $request = $this->createRequestInstance(self::CREATE_DOCUMENT, $index, $type);
        $request->setBody($document);

        return $this->client->send($request);
    }

    /**
     * Deletes a document by id
     *
     * @param string $index
     * @param string $type
     * @param string $id
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function delete($index, $type, $id)
    {
        $request = $this->createRequestInstance(self::DELETE_DOCUMENT, $index, $type);
        $request->setId($id);

        return $this->client->send($request);
    }

    /**
     * gets a document by id
     *
     * @param string $index
     * @param string $type
     * @param string $id
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function get($index, $type, $id)
    {
        $request = $this->createRequestInstance(self::GET_DOCUMENT, $index, $type);
        $request->setId($id);

        return $this->client->send($request);
    }

    /**
     * @param string $index
     * @param string $type
     * @param string $id
     * @param mixed  $document
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function update($index, $type, $id, $document)
    {
        $request = $this->createRequestInstance(self::UPDATE_DOCUMENT, $index, $type);
        $request->setId($id);
        $request->setBody($document);

        return $this->client->send($request);
    }
}

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

use Elastification\Client\Exception\RepositoryException;

interface IndexRepositoryInterface
{
    const INDEX_EXIST = 'IndexExistRequest';
    const INDEX_CREATE = 'CreateIndexRequest';
    const INDEX_DELETE = 'DeleteIndexRequest';
    const INDEX_REFRESH = 'RefreshIndexRequest';
    const INDEX_GET_MAPPING = 'GetMappingRequest';
    const INDEX_CREATE_MAPPING = 'CreateMappingRequest';
    const INDEX_GET_ALIASES = 'GetAliasesRequest';
    const INDEX_UPDATE_ALIASES = 'UpdateAliasesRequest';

    /**
     * Checks if an index exists
     *
     * @param string $index
     *
     * @return bool
     * @author Daniel Wendlandt
     */
    public function exists($index);

    /**
     * Creates an index.
     *
     * @param string $index
     * @param array $body
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt, Aurimas Niekis
     */
    public function create($index, $body = null);

    /**
     * deletes an index.
     *
     * @param string $index
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function delete($index);

    /**
     * refreshes an index.
     *
     * @param string $index
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function refresh($index);

    /**
     * Gets the mapping of all/index/types
     *
     * @param null|string $index
     * @param null|string $type
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function getMapping($index = null, $type = null);

    /**
     * Creates the mapping of all/index/types
     *
     * @param array  $mapping
     * @param string $index
     * @param string $type
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function createMapping(array $mapping, $index, $type);

    /**
     * Gets all aliases based on indices.
     *
     * @param null|string $index
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function getAliases($index = null);

    /**
     * Updates aliases by given actions
     *
     * @param array $aliasActions
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @throws RepositoryException
     * @author Daniel Wendlandt
     */
    public function updateAliases(array $aliasActions);
}

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

use Elastification\Client\Exception\RepositoryClassMapException;

interface RepositoryClassMapInterface
{
    /**
     * classes
     */
    //document
    const CREATE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\CreateDocumentRequest';
    const DELETE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\DeleteDocumentRequest';
    const GET_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\GetDocumentRequest';
    const UPDATE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\UpdateDocumentRequest';
    //search
    const SEARCH_REQUEST = 'Elastification\Client\Request\%s\SearchRequest';
    //index
    const INDEX_EXIST = 'Elastification\Client\Request\%s\Index\IndexExistsRequest';
    const INDEX_CREATE = 'Elastification\Client\Request\%s\Index\CreateIndexRequest';
    const INDEX_DELETE = 'Elastification\Client\Request\%s\Index\DeleteIndexRequest';
    const INDEX_REFRESH = 'Elastification\Client\Request\%s\Index\RefreshIndexRequest';
    const INDEX_GET_MAPPING = 'Elastification\Client\Request\%s\Index\GetMappingRequest';
    const INDEX_CREATE_MAPPING = 'Elastification\Client\Request\%s\Index\CreateMappingRequest';
    const INDEX_GET_ALIASES = 'Elastification\Client\Request\%s\Index\GetAliasesRequest';
    const INDEX_UPDATE_ALIASES = 'Elastification\Client\Request\%s\Index\UpdateAliasesRequest';
    //cat
    const CAT_ALIASES = 'Elastification\Client\Request\%s\Cat\AliasesCatRequest';
    const CAT_ALLOCATION = 'Elastification\Client\Request\%s\Cat\AllocationCatRequest';
    const CAT_COUNT = 'Elastification\Client\Request\%s\Cat\CountCatRequest';
    const CAT_FIELDDATA = 'Elastification\Client\Request\%s\Cat\FielddataCatRequest';
    const CAT_HEALTH = 'Elastification\Client\Request\%s\Cat\HealthCatRequest';
    const CAT_INDICES = 'Elastification\Client\Request\%s\Cat\IndicesCatRequest';
    const CAT_MASTER = 'Elastification\Client\Request\%s\Cat\MasterCatRequest';
    const CAT_NODES = 'Elastification\Client\Request\%s\Cat\NodesCatRequest';
    const CAT_PENDING_TASKS = 'Elastification\Client\Request\%s\Cat\PendingTasksCatRequest';
    const CAT_PLUGINS = 'Elastification\Client\Request\%s\Cat\PluginsCatRequest';
    const CAT_RECOVERY = 'Elastification\Client\Request\%s\Cat\RecoveryCatRequest';
    const CAT_SEGMENTS = 'Elastification\Client\Request\%s\Cat\SegmentsCatRequest';
    const CAT_SHARDS = 'Elastification\Client\Request\%s\Cat\ShardsCatRequest';
    const CAT_THREAD_POOL = 'Elastification\Client\Request\%s\Cat\ThreadPoolCatRequest';

    /**
     * gets the complete namespaces class for a version
     *
     * @param string $class
     *
     * @return string
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    public function getClassName($class);
}

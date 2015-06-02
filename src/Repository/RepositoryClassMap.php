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

use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Exception\RepositoryClassMapException;

class RepositoryClassMap implements RepositoryClassMapInterface
{
    /**
     * all allowed versions go in here
     *
     * @var array
     */
    private $allowedVersions = array(
        ClientVersionMapInterface::VERSION_V090X => ClientVersionMapInterface::VERSION_V090X,
        ClientVersionMapInterface::VERSION_V1X => ClientVersionMapInterface::VERSION_V1X
    );

    /**
     * @var array
     */
    private $classMap = array(
        //document
        'CreateDocumentRequest' => self::CREATE_DOCUMENT_REQUEST,
        'DeleteDocumentRequest' => self::DELETE_DOCUMENT_REQUEST,
        'GetDocumentRequest' => self::GET_DOCUMENT_REQUEST,
        'UpdateDocumentRequest' => self::UPDATE_DOCUMENT_REQUEST,
        //search
        'SearchRequest' => self::SEARCH_REQUEST,
        //index
        'IndexExistRequest' => self::INDEX_EXIST,
        'CreateIndexRequest' => self::INDEX_CREATE,
        'DeleteIndexRequest' => self::INDEX_DELETE,
        'RefreshIndexRequest' => self::INDEX_REFRESH,
        'GetMappingRequest' => self::INDEX_GET_MAPPING,
        'CreateMappingRequest' => self::INDEX_CREATE_MAPPING,
        'GetAliasesRequest' => self::INDEX_GET_ALIASES,
        'UpdateAliasesRequest' => self::INDEX_UPDATE_ALIASES
    );

    /**
     * @var string
     */
    private $versionFolder = ClientVersionMapInterface::VERSION_V1X;

    /**
     * @param null|string $versionFolder
     *
     * @throws RepositoryClassMapException
     */
    public function __construct($versionFolder = null)
    {
        if (null !== $versionFolder) {
            $this->checkVersion($versionFolder);
            $this->versionFolder = $versionFolder;
        }
    }

    /**
     * gets the complete namespaces class for a version
     *
     * @param string $class
     *
     * @return string
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    public function getClassName($class)
    {
        $this->checkRequestClass($class);

        return sprintf($this->classMap[$class], $this->versionFolder);
    }

    /**
     * checks if version folder is defined
     *
     * @param string $versionFolder
     *
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    private function checkVersion($versionFolder)
    {
        if (!isset($this->allowedVersions[$versionFolder])) {
            throw new RepositoryClassMapException('Version folder "' . $versionFolder . '" is not defined');
        }
    }

    /**
     * checks if class shortcut is defined in a version folder
     *
     * @param string $class
     *
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    private function checkRequestClass($class)
    {
        if (!isset($this->classMap[$class])) {
            throw new RepositoryClassMapException('Class "' . $class . '" is not defined');
        }
    }
}

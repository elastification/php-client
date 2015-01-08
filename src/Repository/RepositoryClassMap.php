<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:20
 */

namespace Elastification\Client\Repository;

use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Exception\RepositoryClassMapException;

class RepositoryClassMap implements RepositoryClassMapInterface
{
    /**
     * all allowed versions go in here
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
        'CreateDocumentRequest' => self::CREATE_DOCUMENT_REQUEST,
        'DeleteDocumentRequest' => self::DELETE_DOCUMENT_REQUEST,
        'GetDocumentRequest' => self::GET_DOCUMENT_REQUEST,
        'UpdateDocumentRequest' => self::UPDATE_DOCUMENT_REQUEST
    );

    /**
     * @var string
     */
    private $verionFolder = ClientVersionMapInterface::VERSION_V1X;

    /**
     * @param null|string $versionFolder
     * @throws RepositoryClassMapException
     */
    public function __construct($versionFolder = null)
    {
        if(null !== $versionFolder){
            $this->checkVersion($versionFolder);
            $this->verionFolder = $versionFolder;
        }
    }

    /**
     * gets the complete namespaces class for a version
     *
     * @param string $class
     * @return string
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    public function getClassName($class)
    {
        $this->checkRequestClass($class);
        return sprintf($this->classMap[$class], $this->verionFolder);
    }

    /**
     * checks if version folder is defined
     *
     * @param string $versionFolder
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    private function checkVersion($versionFolder) {
        if(!isset($this->allowedVersions[$versionFolder])) {
            throw new RepositoryClassMapException('Version folder "' . $versionFolder  . '" is not defined');
        }
    }

    /**
     * checks if class shortcut is defined in a version folder
     *
     * @param string $class
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    private function checkRequestClass($class) {
        if(!isset($this->classMap[$class])) {
            throw new RepositoryClassMapException('Class "' . $class  . '" is not defined');
        }
    }
}
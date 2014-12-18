<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:20
 */

namespace Elastification\Client\Repository;

use Elastification\Client\Exception\RepositoryClassMapException;

interface RepositoryClassMapInterface
{
    /**
     * Versions
     */
    const VERSION_V090X = 'V090x';
    const VERSION_V1X = 'V1x';

    /**
     * classes
     */
    const CREATE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\CreateDocumentRequest';
    const DELETE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\DeleteDocumentRequest';
    const GET_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\GetDocumentRequest';

    /**
     * gets the complete namespaces class for a version
     *
     * @param string $class
     * @return string
     * @throws RepositoryClassMapException
     * @author Daniel Wendlandt
     */
    public function getClassName($class);
}
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
     * classes
     */
    //document
    const CREATE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\CreateDocumentRequest';
    const DELETE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\DeleteDocumentRequest';
    const GET_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\GetDocumentRequest';
    const UPDATE_DOCUMENT_REQUEST = 'Elastification\Client\Request\%s\UpdateDocumentRequest';
    //search
    const SEARCH_REQUEST = 'Elastification\Client\Request\%s\SearchRequest';

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
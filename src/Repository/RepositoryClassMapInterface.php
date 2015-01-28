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
    //index
    const INDEX_EXIST = 'Elastification\Client\Request\%s\Index\IndexExistsRequest';
    const INDEX_CREATE = 'Elastification\Client\Request\%s\Index\CreateIndexRequest';
    const INDEX_DELETE = 'Elastification\Client\Request\%s\Index\DeleteIndexRequest';
    const INDEX_REFRESH = 'Elastification\Client\Request\%s\Index\RefreshIndexRequest';
    const INDEX_GET_MAPPING = 'Elastification\Client\Request\%s\Index\GetMappingRequest';
    const INDEX_CREATE_MAPPING = 'Elastification\Client\Request\%s\Index\CreateMappingRequest';
    const INDEX_GET_ALIASES = 'Elastification\Client\Request\%s\Index\GetAliasesRequest';
    const INDEX_UPDATE_ALIASES = 'Elastification\Client\Request\%s\Index\UpdateAliasesRequest';

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
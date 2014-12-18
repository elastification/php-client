<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:11
 */

namespace Elastification\Client\Repository;

interface DocumentRepositoryInterface
{

    const CREATE_DOCUMENT = 'CreateDocumentRequest';
    const DELETE_DOCUMENT = 'DeleteDocumentRequest';
    const GET_DOCUMENT = 'GetDocumentRequest';

    /**
     * creates a document
     *
     * @param string $index
     * @param string $type
     * @param mixed $document
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function create($index, $type, $document);

    /**
     * @param string $index
     * @param string $type
     * @param string $id
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function delete($index, $type, $id);

    /**
     * gets a document by id
     *
     * @param string $index
     * @param string $type
     * @param string $id
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function get($index, $type, $id);

}
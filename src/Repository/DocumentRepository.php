<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:11
 */

namespace Elastification\Client\Repository;

use Elastification\Client\ClientInterface;
use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Serializer\SerializerInterface;

class DocumentRepository extends AbstractRepository implements DocumentRepositoryInterface
{

    /**
     * creates a document
     *
     * @param string $index
     * @param string $type
     * @param mixed $document
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
     * @param mixed $document
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
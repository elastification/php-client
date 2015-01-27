<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:11
 */

namespace Elastification\Client\Repository;

use Elastification\Client\Exception\ClientException;

class IndexRepository extends AbstractRepository implements IndexRepositoryInterface
{

    /**
     * Checks if an index exists
     *
     * @param string $index
     * @return bool
     * @author Daniel Wendlandt
     */
    public function exists($index)
    {
        $request = $this->createRequestInstance(self::INDEX_EXIST, $index, null);

        try {
            $this->client->send($request);
        } catch(ClientException $exception) {
            return false;
        }

        return true;
    }

    /**
     * Creates an index.
     *
     * @param string $index
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function create($index)
    {
        $request = $this->createRequestInstance(self::INDEX_CREATE, $index, null);

        return $this->client->send($request);
    }

    /**
     * deletes an index.
     *
     * @param string $index
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function delete($index)
    {
        $request = $this->createRequestInstance(self::INDEX_DELETE, $index, null);

        return $this->client->send($request);
    }

    /**
     * refreshes an index.
     *
     * @param string $index
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function refresh($index)
    {
        $request = $this->createRequestInstance(self::INDEX_REFRESH, $index, null);

        return $this->client->send($request);
    }

    /**
     * Gets the mapping of all/index/types
     *
     * @param null|string $index
     * @param null|string $type
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function getMapping($index = null, $type = null)
    {
        $request = $this->createRequestInstance(self::INDEX_GET_MAPPING, $index, $type);

        return $this->client->send($request);
    }
}
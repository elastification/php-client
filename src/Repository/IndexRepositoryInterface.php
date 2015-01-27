<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:11
 */

namespace Elastification\Client\Repository;

interface IndexRepositoryInterface
{
    const INDEX_EXIST = 'IndexExistRequest';
    const INDEX_CREATE = 'CreateIndexRequest';
    const INDEX_DELETE = 'DeleteIndexRequest';
    const INDEX_REFRESH = 'RefreshIndexRequest';
    const INDEX_GET_MAPPING = 'GetMappingRequest';
    const INDEX_CREATE_MAPPING = 'CreateMappingRequest';

    /**
     * Checks if an index exists
     *
     * @param string $index
     * @return bool
     * @author Daniel Wendlandt
     */
    public function exists($index);

    /**
     * Creates an index.
     *
     * @param string $index
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function create($index);

    /**
     * deletes an index.
     *
     * @param string $index
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function delete($index);

    /**
     * refreshes an index.
     *
     * @param string $index
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function refresh($index);

    /**
     * Gets the mapping of all/index/types
     *
     * @param null|string $index
     * @param null|string $type
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function getMapping($index = null, $type = null);

    /**
     * Creates the mapping of all/index/types
     *
     * @param array $mapping
     * @param string $index
     * @param string $type
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function createMapping(array $mapping, $index, $type);
}
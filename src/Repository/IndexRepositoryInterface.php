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
}
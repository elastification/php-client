<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/12/14
 * Time: 08:11
 */

namespace Elastification\Client\Repository;

interface SearchRepositoryInterface
{
    const SEARCH = 'SearchRequest';

    /**
     * Creates a new search request, performs a search and returns a SearchResponse.
     * If $query param is empty, an overall search with default elasticsearch
     * search settings for from and size will be performed
     *
     * @param string $index
     * @param string $type
     * @param array $query
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function search($index, $type, array $query = array());
}
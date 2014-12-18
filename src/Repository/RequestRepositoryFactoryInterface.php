<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 18/12/14
 * Time: 17:16
 */

namespace Elastification\Client\Repository;

use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Serializer\SerializerInterface;

interface RequestRepositoryFactoryInterface
{

    /**
     * creates an instance of a request class
     *
     * @param string $class
     * @param string $index
     * @param string $type
     * @param SerializerInterface $serializer
     * @return RequestInterface
     * @author Daniel Wendlandt
     */
    public function create($class, $index, $type, SerializerInterface $serializer);

}
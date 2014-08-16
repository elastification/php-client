<?php

namespace Elastification\Client\Request;

use Elastification\Client\Exception\RequestManagerException;

/**
 * Interface RequestManagerInterface
 * @package Elastification\Client\Request
 * @author Daniel Wendlandt
 */
interface RequestManagerInterface
{
    /**
     * Gets a defined request.
     *
     * @param string $name
     * @return null | RequestInterface
     * @author Daniel Wendlandt
     */
    public function getRequest($name);

    /**
     * Checks if a reguest is registered for a name
     *
     * @param $name
     * @return boolean
     * @author Daniel Wendlandt
     */
    public function hasRequest($name);

    /**
     * removes a request by name.
     * Returns bool for removing an existing request or not
     *
     * @param string $name
     * @return boolean
     * @author Daniel Wendlandt
     */
    public function removeRequest($name);

    /**
     * deletes all requests.
     *
     * @return void
     * @author Daniel Wendlandt
     */
    public function reset();

    /**
     * Sets/registers a request by name
     *
     * @param string $name
     * @param RequestInterface $request
     * @return void
     * @throws RequestManagerException
     * @author Daniel Wendlandt
     */
    public function setRequest($name, RequestInterface $request);

}
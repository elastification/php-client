<?php

namespace Elastification\Client\Request;

use Elastification\Client\Exception\RequestManagerException;

interface RequestManagerInterface
{
    /**
     * Gets a defined request.
     *
     * @param string $name
     * @return null | RequestInterface
     */
    public function getRequest($name);

    /**
     * Checks if a reguest is registered for a name
     *
     * @param $name
     * @return boolean
     */
    public function hasRequest($name);

    /**
     * removes a request by name.
     * Returns bool for removing an existing request or not
     *
     * @param string $name
     * @return boolean
     */
    public function removeRequest($name);

    /**
     * deletes all requests.
     *
     * @return void
     */
    public function reset();

    /**
     * Sets/registers a request by name
     *
     * @param string $name
     * @param RequestInterface $request
     * @return void
     * @throws RequestManagerException
     */
    public function setRequest($name, RequestInterface $request);

}
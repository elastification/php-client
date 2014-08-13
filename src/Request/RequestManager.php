<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 17/06/14
 * Time: 18:41
 */

namespace Elastification\Client\Request;

use Elastification\Client\Exception\RequestManagerException;

class RequestManager implements RequestManagerInterface
{

    /**
     * @var array
     */
    private $requests = array();

    /**
     * @inheritdoc
     */
    public function getRequest($name)
    {
        if($this->requestExists($name)){
            return $this->requests[$name];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function hasRequest($name)
    {
        return $this->requestExists($name);
    }

    /**
     * @inheritdoc
     */
    public function removeRequest($name)
    {
        if($this->requestExists($name)) {
            unset($this->requests[$name]);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        $this->requests = array();
    }

    /**
     * @inheritdoc
     */
    public function setRequest($name, RequestInterface $request)
    {
        if($this->requestExists($name)) {
            throw new RequestManagerException('a request for "' . $name . '" is already registered');
        }

        $this->requests[$name] = $request;
    }

    /**
     * checks if a request is registered for given name
     *
     * @param string $name
     * @return bool
     */
    private function requestExists($name)
    {
        return isset($this->requests[$name]);
    }
}
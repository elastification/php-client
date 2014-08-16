<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Elastification\Client\Request\Shared;

use Elastification\Client\Request\RequestMethods;

abstract class AbstractGetMappingRequest extends AbstractBaseRequest
{

    const REQUEST_ACTION = '_mapping';

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::GET;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return self::REQUEST_ACTION;
    }

    /**
     * get the body
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getBody()
    {
        return null;
    }

    /**
     * before setting data it should be serialized
     *
     * @param mixed $body
     * @throws \Elastification\Client\Exception\RequestException
     * @author Daniel Wendlandt
     */
    public function setBody($body)
    {
        // do nothing here
    }

}
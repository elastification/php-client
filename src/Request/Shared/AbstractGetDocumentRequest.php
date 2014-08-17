<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Elastification\Client\Request\Shared;

use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestMethods;

/**
 * Class AbstractGetDocumentRequest
 * @package Elastification\Client\Request\Shared
 * @author Daniel Wendlandt
 */
abstract class AbstractGetDocumentRequest extends AbstractBaseRequest
{
    /**
     * @var null|string
     */
    private $action = null;

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
        if(null === $this->action) {
            throw new RequestException('id can not be empty for this request');
        }

        return $this->action;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        //do nothing
    }
    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        if(empty($id)) {
            throw new RequestException('Id can not be empty');
        }

        $this->action = $id;
    }

}
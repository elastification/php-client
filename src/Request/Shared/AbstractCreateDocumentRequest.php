<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Elastification\Client\Request\Shared;

use Elastification\Client\Request\RequestMethods;

/**
 * Class AbstractCreateDocumentRequest
 * @package Elastification\Client\Request\Shared
 * @author Daniel Wendlandt
 */
abstract class AbstractCreateDocumentRequest extends AbstractBaseRequest
{
    private $body = null;

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::POST;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        $this->body = $this->serializer->serialize($body, $this->serializerParams);
    }

}
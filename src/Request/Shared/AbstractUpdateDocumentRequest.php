<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 20/06/14
 * Time: 08:08
 */

namespace Elastification\Client\Request\Shared;

use Elastification\Client\Exception\RequestException;
use Elastification\Client\Request\RequestInterface;
use Elastification\Client\Request\RequestMethods;
use Elastification\Client\Serializer\SerializerInterface;

abstract class AbstractUpdateDocumentRequest extends AbstractBaseRequest
{
    /**
     * @var null|string
     */
    private $body = null;

    /**
     * @var null|string
     */
    private $id = null;

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::PUT;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        if(null === $this->id) {
            throw new RequestException('id can not be empty for this request');
        }

        return $this->id;
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
        if(empty($body)) {
            throw new RequestException('Body can not be empty');
        }

        $this->body = $this->serializer->serialize($body, $this->serializerParams);
    }

    /**
     * Sets the document id
     *
     * @param null|string $id
     * @throws \Elastification\Client\Exception\RequestException
     */
    public function setId($id)
    {
        if(empty($id)) {
            throw new RequestException('Id can not be empty');
        }

        $this->id = $id;
    }


}
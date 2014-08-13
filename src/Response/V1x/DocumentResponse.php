<?php
namespace Elastification\Client\Response\V1x;

use Elastification\Client\Exception\ResponseException;
use Elastification\Client\Response\Response;

class DocumentResponse extends Response
{
    /**
     * checks if source exists
     *
     * @return bool
     */
    public function hasSource()
    {
        /*
         * FIXME: This code assumes, that the result of the serializer happening in processData is an array.
         * Maybe we can fix this through a container interface and generic getter/setter
         * e.g. $container->get('_source')?
         */
        $this->processData();

        return isset($this->data['_source']);
    }

    /**
     * gets the source of the response
     *
     * @return array
     * @throws \Elastification\Client\Exception\ResponseException
     */
    public function getSource()
    {
        if(!$this->hasSource()) {
            throw new ResponseException('_source is not set.');
        }

        return $this->data['_source'];
    }

    public function exists()
    {
        $this->processData();

        return $this->data['found'];
    }

    public function getId()
    {
        $this->processData();

        return $this->data['_id'];
    }

    public function getVersion()
    {
        $this->processData();

        return $this->data['_version'];
    }

    public function getIndex()
    {
        $this->processData();

        return $this->data['_index'];
    }

    public function getType()
    {
        $this->processData();

        return $this->data['_type'];
    }
}

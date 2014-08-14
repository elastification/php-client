<?php
namespace Elastification\Client\Response\V090x;

use Elastification\Client\Exception\ResponseException;
use Elastification\Client\Response\Response;

class DocumentResponse extends Response
{

    const PROP_ID = '_id';
    const PROP_SOURCE = '_source';
    const PROP_EXISTS = 'exists';
    const PROP_VERSION = '_version';
    const PROP_INDEX = '_index';
    const PROP_TYPE = '_type';

    /**
     * checks if source exists
     *
     * @return bool
     */
    public function hasSource()
    {
        $this->processData();

        return $this->has(self::PROP_SOURCE);
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
            throw new ResponseException(self::PROP_SOURCE . ' is not set.');
        }

        return $this->get(self::PROP_SOURCE);
    }

    public function exists()
    {
        $this->processData();

        return $this->get(self::PROP_EXISTS);
    }

    public function getId()
    {
        $this->processData();

        return $this->get(self::PROP_ID);
    }

    public function getVersion()
    {
        $this->processData();

        return $this->get(self::PROP_VERSION);
    }

    public function getIndex()
    {
        $this->processData();

        return $this->get(self::PROP_INDEX);
    }

    public function getType()
    {
        $this->processData();

        return $this->get(self::PROP_TYPE);
    }

}

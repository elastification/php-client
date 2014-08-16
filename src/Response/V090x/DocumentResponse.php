<?php
namespace Elastification\Client\Response\V090x;

use Elastification\Client\Exception\ResponseException;
use Elastification\Client\Response\Response;

/**
 * Class DocumentResponse
 * @package Elastification\Client\Response\V090x
 * @author Daniel Wendlandt
 */
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
     * @author Daniel Wendlandt
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
     * @author Daniel Wendlandt
     */
    public function getSource()
    {
        if(!$this->hasSource()) {
            throw new ResponseException(self::PROP_SOURCE . ' is not set.');
        }

        return $this->get(self::PROP_SOURCE);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function exists()
    {
        $this->processData();

        return $this->get(self::PROP_EXISTS);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getId()
    {
        $this->processData();

        return $this->get(self::PROP_ID);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getVersion()
    {
        $this->processData();

        return $this->get(self::PROP_VERSION);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getIndex()
    {
        $this->processData();

        return $this->get(self::PROP_INDEX);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getType()
    {
        $this->processData();

        return $this->get(self::PROP_TYPE);
    }

}

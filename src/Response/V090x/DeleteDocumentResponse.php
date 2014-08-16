<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Elastification\Client\Response\V090x;

use Elastification\Client\Response\Response;

/**
 * Class DeleteDocumentResponse
 * @package Elastification\Client\Response\V090x
 * @author Daniel Wendlandt
 */
class DeleteDocumentResponse extends Response
{

    const PROP_OK = 'ok';
    const PROP_FOUND = 'found';
    const PROP_ID = '_id';
    const PROP_VERSION = '_version';
    const PROP_INDEX = '_index';
    const PROP_TYPE = '_type';

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function isOk()
    {
        $this->processData();

        return $this->get(self::PROP_OK);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function found()
    {
        $this->processData();

        return $this->get(self::PROP_FOUND);
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

<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Elastification\Client\Response\V1x;

use Elastification\Client\Exception\ResponseException;
use Elastification\Client\Response\Response;

class CreateUpdateDocumentResponse extends Response
{

    const PROP_CREATED = 'created';
    const PROP_ID = '_id';
    const PROP_VERSION = '_version';
    const PROP_INDEX = '_index';
    const PROP_TYPE = '_type';

    public function created()
    {
        $this->processData();

        return $this->get(self::PROP_CREATED);
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

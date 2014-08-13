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

class DeleteDocumentResponse extends Response
{

    public function isOk()
    {
        $this->processData();

        return $this->data['ok'];
    }

    public function found()
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

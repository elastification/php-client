<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 10:03
 */

namespace Dawen\Component\Elastic\Response\V090x;

use Dawen\Component\Elastic\Exception\ResponseException;
use Dawen\Component\Elastic\Response\Response;

class CreateUpdateDocumentResponse extends Response
{

    public function isOk()
    {
        $this->processData();

        return $this->data['ok'];
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

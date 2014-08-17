<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace Elastification\Client\Response\V1x;

use Elastification\Client\Exception\ResponseException;
use Elastification\Client\Response\Response;

/**
 * @package Elastification\Client\Response\V1x
 * @author  Daniel Wendlandt
 */
class DocumentResponse extends Response
{
    const PROP_ID = '_id';
    const PROP_SOURCE = '_source';
    const PROP_FOUND = 'found';
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
        if (!$this->hasSource()) {
            throw new ResponseException(self::PROP_SOURCE . ' is not set.');
        }

        return $this->get(self::PROP_SOURCE);
    }

    public function found()
    {
        $this->processData();

        return $this->get(self::PROP_FOUND);
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

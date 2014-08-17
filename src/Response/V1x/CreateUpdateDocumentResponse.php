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

use Elastification\Client\Response\Response;

/**
 * @package Elastification\Client\Response\V1x
 * @author  Daniel Wendlandt
 */
class CreateUpdateDocumentResponse extends Response
{

    const PROP_CREATED = 'created';
    const PROP_ID = '_id';
    const PROP_VERSION = '_version';
    const PROP_INDEX = '_index';
    const PROP_TYPE = '_type';

    /**
     * @return boolean
     * @author Mario Mueller
     */
    public function created()
    {
        $this->processData();

        return $this->get(self::PROP_CREATED);
    }

    /**
     * @return string
     * @author Mario Mueller
     */
    public function getId()
    {
        $this->processData();

        return $this->get(self::PROP_ID);
    }

    /**
     * @return integer
     * @author Mario Mueller
     */
    public function getVersion()
    {
        $this->processData();

        return $this->get(self::PROP_VERSION);
    }

    /**
     * @return string
     * @author Mario Mueller
     */
    public function getIndex()
    {
        $this->processData();

        return $this->get(self::PROP_INDEX);
    }

    /**
     * @return string
     * @author Mario Mueller
     */
    public function getType()
    {
        $this->processData();

        return $this->get(self::PROP_TYPE);
    }
}

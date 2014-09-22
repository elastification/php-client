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
 * Class IndexStatsResponse
 *
 * @package Elastification\Client\Response\V1x\Index
 * @author  Daniel Wendlandt
 */
class CountResponse extends Response
{

    const PROP_COUNT = 'count';
    const PROP_SHARDS = '_shards';

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getCount()
    {
        $this->processData();

        return $this->get(self::PROP_COUNT);
    }

    /**
     * Getter Method
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getShards()
    {
        $this->processData();

        return $this->get(self::PROP_SHARDS);
    }

}

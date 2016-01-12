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

namespace Elastification\Client\Repository;

use Elastification\Client\ClientVersionMapInterface;
use Elastification\Client\Exception\RepositoryException;

class CatRepository extends AbstractRepository implements CatRepositoryInterface
{

    /**
     * Gets aliases from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function aliases()
    {
        $request = $this->createRequestInstance(self::CAT_ALIASES, null, null);

        return $this->client->send($request);
    }

    /**
     * Gets allocation from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function allocation()
    {
        $request = $this->createRequestInstance(self::CAT_ALLOCATION, null, null);

        return $this->client->send($request);
    }

    /**
     * Gets count from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function count()
    {
        $request = $this->createRequestInstance(self::CAT_COUNT, null, null);

        return $this->client->send($request);
    }

    /**
     * Gets fielddata from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function fielddata()
    {
        $request = $this->createRequestInstance(self::CAT_FIELDDATA, null, null);

        return $this->client->send($request);
    }

    /**
     * Gets health from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function health()
    {
        $request = $this->createRequestInstance(self::CAT_HEALTH, null, null);

        return $this->client->send($request);
    }

    /**
     * Gets indices from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function indices()
    {
        $request = $this->createRequestInstance(self::CAT_INDICES, null, null);

        return $this->client->send($request);
    }

    /**
     * gets the right class string of a version
     *
     * @param string $class
     *
     * @return string
     * @throws RepositoryException
     * @author Daniel Wendlandt
     */
    protected function getClass($class)
    {
        if(ClientVersionMapInterface::VERSION_V090X === $this->versionFolder) {
            throw new RepositoryException('Version folder ' .
                ClientVersionMapInterface::VERSION_V090X . ' is not allowed for cat repository');
        }

        return parent::getClass($class);
    }

}

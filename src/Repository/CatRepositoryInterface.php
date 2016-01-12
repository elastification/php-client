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

interface CatRepositoryInterface
{
    const CAT_ALIASES = 'AliasesCatRequest';
    const CAT_ALLOCATION = 'AllocationCatRequest';
    const CAT_COUNT = 'CountCatRequest';
    const CAT_FIELDDATA = 'FielddataCatRequest';
    const CAT_HEALTH = 'HealthCatRequest';
    const CAT_INDICES = 'IndicesCatRequest';
    const CAT_MASTER = 'MasterCatRequest';
    const CAT_NODES = 'NodesCatRequest';
    const CAT_PENDING_TASKS = 'PendingTasksCatRequest';
    const CAT_PLUGINS = 'PluginsCatRequest';
    const CAT_RECOVERY = 'RecoveryCatRequest';
    const CAT_SEGMENTS = 'SegmentsCatRequest';
    const CAT_SHARDS = 'ShardsCatRequest';
    const CAT_THREAD_POOL = 'ThreadPoolCatRequest';

    /**
     * Gets aliases from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     * @author Daniel Wendlandt
     */
    public function aliases();

    /**
     * Gets allocation from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function allocation();

    /**
     * Gets count from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function count();

    /**
     * Gets fielddata from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function fielddata();

    /**
     * Gets health from cat api
     *
     * @return \Elastification\Client\Response\ResponseInterface
     */
    public function health();

}

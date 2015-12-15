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
namespace Elastification\Client\Response\Shared\Cat;

use Elastification\Client\Response\Response;

/**
 * Class AbstractAliasesCatResponse
 *
 * @package Elastification\Client\Response\Shared\Cat
 * @author  Daniel Wendlandt
 */
abstract class AbstractAliasesCatResponse extends Response
{
    const PROP_ALIAS = 'alias';
    const PROP_INDEX = 'index';
    const PROP_FILTER = 'filter';
    const PROP_ROUTING_INDEX = 'routing.index';
    const PROP_ROUTING_SEARCH = 'routing.search';

    /**
     * @inheritdoc
     */
    protected function processData()
    {
        if (null === $this->data) {
            $rawData = $this->getRawData();

            if (null !== $rawData) {
                $this->data = $this->processCatData($rawData);
            }
        }
    }

    /**
     * This is our serializer ;)
     *
     * @param string $rawData
     *
     * @return array
     */
    protected function processCatData($rawData)
    {
        $data = array();
        $properties = $this->getProperties();
        $rows = explode(PHP_EOL, $rawData);

        foreach ($rows as $row) {
            if (mb_strlen($row) > 3) {
                $fields = array_filter(explode(' ', $row));

                $data[] = array_combine($properties, $fields);
            }
        }

        return $data;
    }

    /**
     * Provides properties of this cat request
     *
     * @return array
     */
    protected function getProperties()
    {
        return array(
            self::PROP_ALIAS,
            self::PROP_INDEX,
            self::PROP_FILTER,
            self::PROP_ROUTING_INDEX,
            self::PROP_ROUTING_SEARCH
        );
    }
}

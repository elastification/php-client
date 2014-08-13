<?php
/**
 * Created by PhpStorm.
 * User: dwendlandt
 * Date: 24/06/14
 * Time: 07:59
 */

namespace Elastification\Client;

use Elastification\Client\Exception\ClientException;

final class ClientVersionMap
{
    private static $versions = array(
        '0.90.x' => 'V090x'
    );

    /**
     * Gets the defined version for folders
     *
     * @param string $version
     * @return string
     * @throws Exception\ClientException
     */
    public static function getVersion($version)
    {
        if(!isset(self::$versions[$version])) {
            throw new ClientException('version ' . $version . ' is not defined');
        }

        return self::$versions[$version];
    }
}

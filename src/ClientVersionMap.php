<?php
namespace Elastification\Client;

use Elastification\Client\Exception\ClientException;

/**
 * The version map.
 *
 * Elastification's php client support the use of multiple elasticsearch versions.
 * This class shows all supported versions and their mapping to our version command
 * namespaces/folders.
 *
 * @package Elastification\Client
 * @author  Daniel Wendlandt
 */
final class ClientVersionMap
{
    private static $versions = array(
        '0.90.x' => 'V090x',
        '1.0.x' => 'V1X',
        '1.1.x' => 'V1X',
        '1.2.x' => 'V1X',
        '1.3.x' => 'V1X',
    );

    /**
     * Get the defined version for folders.
     * We throw an exception in case of a non-matching version.
     *
     * @param   string $version A version string in the schema of 0.90.x.
     *
     * @return  string The internal namespace/folder
     * @throws Exception\ClientException
     * @author Daniel Wendlandt
     */
    public static function getVersion($version)
    {
        if (!isset(self::$versions[$version])) {
            throw new ClientException('version ' . $version . ' is not defined');
        }

        return self::$versions[$version];
    }
}

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
namespace Elastification\Client;

use Elastification\Client\Exception\VersionException;

/**
 * The version map. Elastification's php client support the use of multiple elasticsearch versions.
 *
 * This class shows all supported versions and their mapping to our version command
 * namespaces/folders.
 *
 * @package Elastification\Client
 * @author  Daniel Wendlandt
 */
class ClientVersionMap implements ClientVersionMapInterface
{
    private $versions = array(
        array('regex' => '/0\.90.*/', 'version' => 'V090x'),
        array('regex' => '/1\..*/', 'version' => 'V1x')
    );

    /**
     * Get the defined version for folders.
     * We throw an exception in case of a non-matching version.
     *
     * @param  string $version A version string in the schema of 0.90.x.
     * @return string The internal namespace/folder
     * @throws VersionException
     * @author Daniel Wendlandt
     */
    public function getVersion($version)
    {

        foreach($this->versions as $versionPattern) {
            if(preg_match($versionPattern['regex'], $version)) {
                return $versionPattern['version'];
            }
        }

        throw new VersionException('Version not found');
    }
}

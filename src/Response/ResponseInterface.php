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
namespace Elastification\Client\Response;

use Elastification\Client\Serializer\SerializerInterface;

/**
 * Interface ResponseInterface
 *
 * @package Elastification\Client\Response
 * @author  Daniel Wendlandt
 */
interface ResponseInterface
{
    /**
     * @param string              $rawData
     * @param SerializerInterface $serializer
     * @param array               $serializerParams
     *
     * @author Daniel Wendlandt
     * @return void
     */
    public function __construct($rawData, SerializerInterface $serializer, array $serializerParams = array());

    /**
     * Gets the converted data of the response
     *
     * @return mixed
     * @author Daniel Wendlandt
     */
    public function getData();

    /**
     * Gets the raw not converted data of the response
     *
     * @return string
     * @author Daniel Wendlandt
     */
    public function getRawData();

    /**
     * Gets the current defined serializer
     *
     * @return SerializerInterface
     * @author Daniel Wendlandt
     */
    public function getSerializer();
}

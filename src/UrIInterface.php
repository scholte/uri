<?php
namespace Scholte;

use Scholte\Uri\HostInterface;
use Scholte\Uri\QueryParametersInterface;

/**
 * Interface UriInterface
 *
 * @package Scholte
 */
interface UriInterface
{
    /**
     * Change to string
     *
     * @return string
     */
    public function __toString() : string;

    /**
     * Parse URI
     *
     * @param string $uri
     *
     * @return $this
     */
    public function parse(string $uri);

    /**
     * Build URI
     *
     * @param int $filter
     *
     * @return string
     */
    public function getUri(int $filter = 0);

    /**
     * Get scheme
     *
     * @return string
     */
    public function getScheme() : string;

    /**
     * Get host
     *
     * @return HostInterface
     */
    public function getHost() : HostInterface;

    /**
     * Get port
     *
     * @return int
     */
    public function getPort() : int;

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() : string;

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() : string;

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string
     */
    public function getAuthority() : string;

    /**
     * Get path
     *
     * @return string
     */
    public function getPath();

    /**
     * Retr;ieve the query string of the URI.
     *
     * @return QueryParametersInterface
     */
    public function getQuery() : QueryParametersInterface;

    /**
     * Get fragment
     *
     * @return string
     */
    public function getFragment() : string;
}

<?php
declare(strict_types = 1);

namespace Scholte;


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
     * @return string
     */
    public function getHost() : string;

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
     * @return string
     */
    public function getQuery() : string;

    /**
     * Get fragment
     *
     * @return string
     */
    public function getFragment() : string;
}

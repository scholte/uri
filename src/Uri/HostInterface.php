<?php
namespace Scholte\Uri;

/**
 * Interface HostInterface
 *
 * @package Scholte\Uri
 */
interface HostInterface
{
    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString() : string;

    /**
     * Parse host
     *
     * @param string $host
     */
    public function parse(string $host);

    /**
     * Get host
     *
     * @param int $filter
     *
     * @return string
     */
    public function getHost(int $filter = 0) : string;

    /**
     * Get IP
     *
     * @return string
     */
    public function getIp() : string;

    /**
     * Get sub domain
     *
     * @return string
     */
    public function getSubDomain() : string;

    /**
     * Get root domain
     *
     * @return string
     */
    public function getRootDomain() : string;

    /**
     * Get top level domain
     *
     * @return string
     */
    public function getTopLevelDomain() : string;
}

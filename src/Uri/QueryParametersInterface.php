<?php
declare(strict_types = 1);

namespace Scholte\Uri;

/**
 * Interface QueryParametersInterface
 * @package Scholte\Uri
 */
interface QueryParametersInterface
{
    /**
     * Change to string
     *
     * @return mixed
     */
    public function __toString() : string;

    /**
     * Parse parameters
     *
     * @param string $query
     *
     * @return $this
     */
    public function parse(string $query);

    /**
     * Get query parameters
     *
     * @return string
     */
    public function getQueryParameters() : string;

    /**
     * Add value
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addValue(string $key, string $value);

    /**
     * Remove value
     *
     * @param string $key
     *
     * @return $this
     */
    public function removeValue(string $key);

    /**
     * Get value
     *
     * @param string $key
     *
     * @return string
     */
    public function getValue(string $key) : string;
}

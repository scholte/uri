<?php
declare(strict_types = 1);

namespace Scholte\Uri;

/**
 * Class QueryParameters
 *
 * @package Scholte\Uri
 */
class QueryParameters implements QueryParametersInterface
{
    /**
     * Query parameters
     * @var array
     */
    private $values = [];

    /**
     * Constructor
     *
     * @param string $queryParameters
     */
    public function __construct(string $queryParameters = '')
    {
        if (!empty($queryParameters)) {
            $this->parse($queryParameters);
        }
    }

    /**
     * Change to string
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->getQueryParameters();
    }

    /**
     * Parse parameters
     *
     * @param string $query
     *
     * @return $this
     */
    public function parse(string $query)
    {
        if (empty($query)) {
            return $this;
        }

        parse_str($query, $parsed);

        $this->setValues($parsed);

        return $this;
    }

    /**
     * Get query parameters
     *
     * @return string
     */
    public function getQueryParameters() : string
    {
        $parameters = '';
        if (!empty($this->values)) {
            $parameters .= http_build_query($this->values);
        }

        return $parameters;
    }

    /**
     * Set values
     *
     * @param array $values
     *
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = [];
        foreach ($values as $key => $value) {
            $this->addValue($key, $value);
        }

        return $this;
    }

    /**
     * Add value
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addValue(string $key, string $value)
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * Remove value
     * 
     * @param string $key
     * 
     * @return $this
     */
    public function removeValue(string $key)
    {
        if (isset($this->values[$key])) {
            unset($this->values[$key]);
        }
        
        return $this;
    }

    /**
     * Get value
     *
     * @param string $key
     *
     * @return string
     */
    public function getValue(string $key) : string
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
    }

    /**
     * Get values
     *
     * @return array
     */
    public function getValues() : array
    {
        return $this->values;
    }
}

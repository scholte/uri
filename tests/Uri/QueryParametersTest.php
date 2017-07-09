<?php
namespace Scholte\Uri;

use PHPUnit\Framework\TestCase;

/**
 * Class QueryParametersTest
 *
 * @package Scholte\Uri
 */
class QueryParametersTest extends TestCase
{
    /**
     * Test parsing parameter
     */
    public function testParseParameter()
    {
        $queryParameters = new QueryParameters('key=value');
        
        $this->assertEquals(['key' => 'value'], $queryParameters->getValues());
    }

    /**
     * Test parsing parameters
     */
    public function testParseMultipleParameters()
    {
        $queryParameters = new QueryParameters('key=value&key2=value2');
        
        $this->assertEquals(['key' => 'value', 'key2' => 'value2'], $queryParameters->getValues());
    }

    /**
     * Test adding parameter
     */
    public function testAddValue()
    {
        $queryParameters = new QueryParameters('key=value');
        $queryParameters->addValue('key2', 'value2');
        
        $this->assertEquals(['key' => 'value', 'key2' => 'value2'], $queryParameters->getValues());
    }

    /**
     * Test removing parameter
     */
    public function testRemoveValue()
    {
        $queryParameters = new QueryParameters('key=value&key2=value2');
        $queryParameters->removeValue('key2');
        
        $this->assertEquals(['key' => 'value'], $queryParameters->getValues());
    }

    /**
     * Test retrieving query parameters
     */
    public function testGetQueryParameters()
    {
        $queryParameters = new QueryParameters('key=value&key2=value2');
        
        $this->assertEquals('key=value&key2=value2', $queryParameters->getQueryParameters());
    }

    /**
     * Test retrieving query parameters with special characters
     */
    public function testSpecialCharacters()
    {
        $queryParameters = new QueryParameters('key=val%ué');
        
        $this->assertEquals('key=val%25u%C3%A9', $queryParameters->getQueryParameters());
    }
}

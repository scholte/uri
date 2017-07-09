<?php
namespace Scholte\Uri;

use PHPUnit\Framework\TestCase;

/**
 * Class HostTest
 *
 * @package Scholte\Uri
 */
class HostTest extends TestCase
{
    /**
     * Test subdomain using a full host value
     */
    public function testParseSubdomainFullHost()
    {
        $host = new Host('www.test.com');
        $this->assertEquals('www', $host->getSubDomain());
    }

    /**
     * Test subdomain using a host value without a subdomain
     */
    public function testParseSubdomainMissingSubdomain()
    {    
        $host = new Host('test.com');
        $this->assertEmpty($host->getSubDomain());
        
        $host = new Host('.test.com');
        $this->assertEmpty($host->getSubDomain());
    }

    /**
     * Test root domain using a full host value
     */
    public function testParseRootDomainFullHost()
    {
        $host = new Host('www.test.com');
        $this->assertEquals('test', $host->getRootDomain());
    }

    /**
     * Test root domain using a host value without a subdomain
     */
    public function testParseRootDomainMissingSubDomain()
    {
        $host = new Host('test.com');
        $this->assertEquals('test', $host->getRootDomain());
    
        $host = new Host('.test.com');
        $this->assertEquals('test', $host->getRootDomain());
    }

    /**
     * Test top level domain using a full host value
     */
    public function testParseTopLevelDomainFullHost()
    {
        $host = new Host('www.test.com');
        $this->assertEquals('com', $host->getTopLevelDomain());
    }

    /**
     * Test top level domain using a host without a subdomain
     */
    public function testParseTopLevelDomainMissingSubDomain()
    {
        $host = new Host('test.com');
        $this->assertEquals('com', $host->getTopLevelDomain());
    }

    /**
     * Test host when using an IP address
     */
    public function testParseIp()
    {
        $host = new Host('192.168.1.1');
        $this->assertEquals('192.168.1.1', $host->getIp());
    }

    /**
     * Test providing an invalid IP address
     * 
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid IP address provided
     */
    public function testExceptionProvidingInvalidIp()
    {
        $host = new Host();
        $host->setIp('test');
    }

    /**
     * Test retrieving the host when a full Host is set 
     */
    public function testGetHostToStringFullHost()
    {
        $host = new Host('www.test.com');
        
        $this->assertEquals('www.test.com', $host->getHost());
    }

    /**
     * Test retrieving the host with a host without a subdomain
     */
    public function testGetHostToStringMissingSubDomain()
    {
        $host = new Host('test.com');
        $this->assertEquals('test.com', $host->getHost());
    }

    /**
     * Test retrieving the host when an IP address is set
     */
    public function testGetHostToStringIp()
    {
        $host = new Host('192.168.1.1');
        $this->assertEquals('192.168.1.1', $host->getHost());
    }

    /**
     * Test retrieving the host when the host is incomplete
     */
    public function testGetHostIncomplete()
    {
        $host = new Host();
        $host->setSubDomain('www');
        $host->setRootDomain('test');

        $this->assertEquals('test', $host->getHost());
    }

    /**
     * Test retrieving the host when subdomain, rootdomain and top level domain is set
     */
    public function testGetHost()
    {
        $host = new Host();
        $host->setSubDomain('www');
        $host->setRootDomain('test');
        $host->setTopLevelDomain('com');
        
        $this->assertEquals('www.test.com', $host->getHost());
    }

    /**
     * Test retrieving the host without a subdomain
     */
    public function testGetHostStrippedSubDomain()
    {
        $host = new Host('www.test.com');
        
        $this->assertEquals('test.com', $host->getHost(URI_STRIP_SUB_DOMAIN));
    }

    /**
     * Test retrieving host when localhost is set
     */
    public function testGetHostLocalhost()
    {
        $host = new Host('localhost');
        
        $this->assertEquals('localhost', $host->getHost());
    }
}

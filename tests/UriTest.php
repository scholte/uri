<?php
namespace Scholte;

use PHPUnit\Framework\TestCase;
use Scholte\Uri\QueryParameters;
use Scholte\Uri\Host;

/**
 * Class UriTest
 *
 * @package Scholte
 */
class UriTest extends TestCase
{
    /**
     * Test scheme parsing an URI
     */
    public function testParseSchemeFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme', $uri->getScheme());
    }

    /**
     * Test username parsing an URI
     */
    public function testParseUsernameFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('user', $uri->getUsername());
    }

    /**
     * Test password paring an URI
     */
    public function testParsePasswordFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('password', $uri->getPassword());
    }

    /**
     * Test host parsing an URI
     */
    public function testParseHostFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('www.host.com', $uri->getHost());
    }

    /**
     * Test port parsing an URI
     */
    public function testParsePortFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $this->assertEquals(80, $uri->getPort());
    }

    /**
     * Test path parsing an URI
     */
    public function testParsePathFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('/path', $uri->getPath());
    }

    /**
     * Test query parameters parsing an URI
     */
    public function testParseQueryParametersFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('parameter=value', $uri->getQuery());
    }

    /**
     * Test fragment parsing an URI
     */
    public function testParseFragmentFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('fragment', $uri->getFragment());
    }

    public function testParseRelativeUri()
    {
        $uri = new Uri('/path?parameter=value#fragment');
        
        $this->assertEquals('/path?parameter=value#fragment', $uri->getUri());
    }

    /**
     * Test localhost
     */
    public function testParseLocalhost()
    {
        $uri = new Uri('http://localhost');
        $this->assertEquals('localhost', $uri->getHost());
        
        $uri = new Uri('localhost');
        $this->assertEquals('localhost', $uri->getHost());
    }

    /**
     * Test URI without a scheme
     */
    public function testMissingScheme()
    {
        $uri = new Uri('//test.com');
        
        $this->assertEquals('//test.com', $uri->getUri());
    }

    /**
     * Test parsing a file URI
     */
    public function testParseFileUri()
    {
        $uri = new Uri('file:///test.txt');

        $this->assertEquals('file', $uri->getScheme());
        $this->assertEquals('/test.txt', $uri->getPath());
        $this->assertEquals('file:///test.txt', $uri->getUri());
    }

    /**
     * Test retrieving URI when a full URI is provided
     */
    public function testGetUriFullUri()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://user:password@www.host.com:80/path?parameter=value#fragment', $uri->getUri());
    }

    /**
     * Test retrieving URI without scheme
     */
    public function testGetUriStrippedScheme()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('//user:password@www.host.com:80/path?parameter=value#fragment', $uri->getUri(URI_STRIP_SCHEME));
    }

    /**
     * Test retrieving URI without host
     */
    public function testGetUriStrippedHost()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://user:password:80/path?parameter=value#fragment', $uri->getUri(URI_STRIP_HOST));
    }

    /**
     * Test retrieving URI without port
     */
    public function testGetUriStrippedPort()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://user:password@www.host.com/path?parameter=value#fragment', $uri->getUri(URI_STRIP_PORT));
    }

    /**
     * Test with a default port
     */
    public function testGetUriWithDefaultPort()
    {
        $uri = new Uri('http://user:password@www.host.com:80/path?parameter=value#fragment');

        $this->assertEquals('http://user:password@www.host.com/path?parameter=value#fragment', $uri->getUri());
    }

    /**
     * Test without a default port
     */
    public function testGetUriWithoutDefaultPort()
    {
        $uri = new Uri('http://user:password@www.host.com:8080/path?parameter=value#fragment');

        $this->assertEquals('http://user:password@www.host.com:8080/path?parameter=value#fragment', $uri->getUri());
    }

    /**
     * Test retrieving URI without user info
     */
    public function testGetUriStrippedUserInfo()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://www.host.com:80/path?parameter=value#fragment', $uri->getUri(URI_STRIP_USERINFO));
    }

    /**
     * Test retrieving URI without path
     */
    public function testGetUriStrippedPath()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://user:password@www.host.com:80?parameter=value#fragment', $uri->getUri(URI_STRIP_PATH));
    }

    /**
     * Test retrieving URI without query parameters
     */
    public function testgetUriStrippedQueryParameters()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://user:password@www.host.com:80/path#fragment', $uri->getUri(URI_STRIP_QUERY));
    }

    /**
     * Test retrieving URI without fragment
     */
    public function testGetUriStrippedFragment()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://user:password@www.host.com:80/path?parameter=value', $uri->getUri(URI_STRIP_FRAGMENT));
    }

    /**
     * Test retrieving only a scheme and host
     */
    public function testGetUriStrippedForHost()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('scheme://www.host.com', $uri->getUri(URI_HOST));
    }

    /**
     * Test retrieving only a path
     */
    public function testGetUriStrippedForPath()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
        
        $this->assertEquals('/path?parameter=value#fragment', $uri->getUri(URI_PATH));
    }

    /**
     * Test building file URI
     */
    public function testBuildFileUri()
    {
        $uri = new Uri();
        $uri
            ->setScheme('file')
            ->setPath('/test.txt');
            
        $this->assertEquals('file:///test.txt', $uri->getUri());
    }

    /**
     * Test building a relative URI
     */
    public function testBuildRelativeUri()
    {
        $uri = new Uri();
        $uri
            ->setPath('/test')
            ->setQuery(new QueryParameters('key=value'));
            
        $this->assertEquals('/test?key=value', $uri->getUri(URI_PATH));
    
        $uri->setScheme('');
        $this->assertEquals('/test?key=value', $uri->getUri());
    }

    /**
     * Test building URI without a scheme
     */
    public function testBuildUriWithoutScheme()
    {
        $uri = new Uri();
        $uri
            ->setScheme('')
            ->setHost(new Host('www.test.com'))
            ->setPath('/test');
        
        $this->assertEquals('//www.test.com/test', $uri->getUri());
    }

    public function getGetAuthority()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withUserInfo('key', 'value');
        $this->assertEquals('user:password@www.host.com:80', $newUri->getAuthority());
    }

    /**
     * Test changing scheme
     */
    public function testWithScheme()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withScheme('http');
        $this->assertEquals('http', $newUri->getScheme());
    }

    /**
     * Test changing user info
     */
    public function testWithUserInfo()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withUserInfo('key', 'value');
        $this->assertEquals('key', $newUri->getUsername());
        $this->assertEquals('value', $newUri->getPassword());
    }

    /**
     * Test changing host
     */
    public function testWithHost()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withHost('www.test.nl');
        $this->assertEquals('www.test.nl', $newUri->getHost());
    }

    /**
     * Test changing port
     */
    public function testWithPort()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withPort(8080);
        $this->assertEquals(8080, $newUri->getPort());
    }

    /**
     * Test changing path
     */
    public function testWithPath()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withPath('/newPath');
        $this->assertEquals('/newPath', $newUri->getPath());
    }

    /**
     * Test changin query
     */
    public function testWithQuery()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withQuery('key=test');
        $this->assertEquals('key=test', $newUri->getQuery());
    }

    /**
     * Test changing fragment
     */
    public function testWithFragment()
    {
        $uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');

        $newUri = $uri->withFragment('value');
        $this->assertEquals('value', $newUri->getFragment());
    }
}

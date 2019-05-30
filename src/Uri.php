<?php
declare(strict_types = 1);

namespace Scholte;

use Psr\Http\Message\UriInterface as PsrUriInterface;
use Scholte\Uri\HostInterface;
use Scholte\Uri\Host;
use Scholte\Uri\QueryParametersInterface;
use Scholte\Uri\QueryParameters;

define('URI_STRIP_SCHEME', 1);
define('URI_STRIP_HOST', 2);
define('URI_STRIP_PORT', 4);
define('URI_STRIP_USERINFO', 8);
define('URI_STRIP_PATH', 16);
define('URI_STRIP_QUERY', 32);
define('URI_STRIP_FRAGMENT', 64);
define('URI_HOST', URI_STRIP_USERINFO | URI_STRIP_PORT | URI_STRIP_PATH | URI_STRIP_QUERY | URI_STRIP_FRAGMENT);
define('URI_PATH', URI_STRIP_SCHEME | URI_STRIP_HOST | URI_STRIP_USERINFO | URI_STRIP_PORT);
define('URI_USERINFO', URI_STRIP_SCHEME | URI_STRIP_HOST | URI_STRIP_PORT | URI_STRIP_PATH | URI_STRIP_QUERY | URI_STRIP_FRAGMENT);
define('URI_AUTHORITY', URI_STRIP_SCHEME | URI_STRIP_PATH | URI_STRIP_QUERY | URI_STRIP_FRAGMENT);

/**
 * Class Uri
 * RFC 3986
 *
 * @package Scholte
 */
class Uri implements PsrUriInterface, UriInterface
{
    /**
     * Scheme
     * @var string
     */
    private $scheme = '';

    /**
     * Host
     * @var HostInterface
     */
    private $host = null;

    /**
     * Port
     * @var int
     */
    private $port;

    /**
     * Username
     * @var string
     */
    private $username;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Path
     * @var string
     */
    private $path;

    /**
     * Query parameters
     * @var QueryParametersInterface
     */
    private $queryParameters = null;

    /**
     * Fragment
     * @var string
     */
    private $fragment;

    /**
     * List of ports and schemes
     * @var array
     */
    private $defaultSchemePorts = [
        21 => 'ftp',
        22 => 'ssh',
        80 => 'http',
        443 => 'https'
    ];

    /**
     * Double slashes
     * @var array
     */
    private $doubleSlashesSchemes = [
        'file'
    ];

    /**
     * No double slashes
     * @var array
     */
    private $hasNoDoubleSlashesSchemes = [
        'about', 'mailto', 'opera', 'data'
    ];

    /**
     * Constructor
     *
     * @param string                   $uri
     * @param HostInterface            $host
     * @param QueryParametersInterface $queryParameters
     */
    public function __construct(string $uri = '', HostInterface $host = null, QueryParametersInterface $queryParameters = null)
    {
        // Default values
        if ($host === null) {
            $host = new Host();
        }
        $this->setHost($host);

        if ($queryParameters === null) {
            $queryParameters = new QueryParameters();
        }
        $this->setQuery($queryParameters);

        if (!empty($uri)) {
            $this->parse($uri);
        }
    }

    /**
     * Change to string
     *
     * @return mixed
     */
    public function __toString() : string
    {
        return $this->getUri();
    }

    /**
     * Parse URI
     *
     * @param string $uri
     *
     * @return $this
     */
    public function parse(string $uri)
    {
        $parseInfo = $this->parseUriToDefaultInfo($uri);

        $this
            ->setScheme($parseInfo['scheme'])
            ->setHost(new Host($parseInfo['host']))
            ->setUserInfo($parseInfo['user'], $parseInfo['pass'])
            ->setQuery(new QueryParameters($parseInfo['query']))
            ->setPath($parseInfo['path'])
            ->setFragment($parseInfo['fragment'])
        ;

        if (!empty($parseInfo['port'])) {
            $this->setPort((int) $parseInfo['port']);
        }

        return $this;
    }

    /**
     * Get URI
     *
     * @param int $filter
     *
     * @return string
     */
    public function getUri(int $filter = 0) : string
    {
        $defaultInfo = $this->getDefaultInfoByFilter($filter);

        return $this->parseDefaultInfoToUri($defaultInfo);
    }

    /**
     * Get default info using available values and filters
     *
     * @param int $filter
     *
     * @return array
     */
    private function getDefaultInfoByFilter(int $filter) : array
    {
        $defaultInfo = [
            'scheme' => '',
            'host' => '',
            'port' => '',
            'user' => '',
            'pass' => '',
            'path' => '',
            'query' => '',
            'fragment' => ''
        ];

        // Scheme
        if (!empty($this->scheme) && !($filter & URI_STRIP_SCHEME)) {
            $defaultInfo['scheme'] = $this->scheme;
        }

        // Userinfo
        if (!empty($this->username) && !($filter & URI_STRIP_USERINFO)) {
            $defaultInfo['user'] = $this->username;

            if (!empty($this->password)) {
                $defaultInfo['pass'] = $this->password;
            }
        }

        // Host
        if (!($filter & URI_STRIP_HOST)) {
            $defaultInfo['host'] = $this->host->getHost($filter);
        }

        // Port
        if (!empty($this->port) && !($filter & URI_STRIP_PORT)) {
            // Check for default port according to scheme
            if (!$this->isDefaultPort($this->port, $this->scheme)) {
                $defaultInfo['port'] = $this->port;
            }
        }

        // Path
        if (!($filter & URI_STRIP_PATH)) {
            $defaultInfo['path'] = $this->path;
        }

        // Query
        if (!($filter & URI_STRIP_QUERY)) {
            $queryParameters = (string) $this->getQuery();
            if (!empty($queryParameters)) {
                $defaultInfo['query'] = $queryParameters;
            }
        }

        // Fragment
        if (!empty($this->fragment) && !($filter & URI_STRIP_FRAGMENT)) {
            $defaultInfo['fragment'] = $this->fragment;
        }

        return $defaultInfo;
    }

    /**
     * Parse default info and return an URI
     *
     * @param array $defaultInfo
     *
     * @return string
     */
    private function parseDefaultInfoToUri(array $defaultInfo) : string
    {
        $uri = '';

        // Scheme
        if (!empty($defaultInfo['scheme'])) {
            $uri .= $defaultInfo['scheme'] . ':';
        }

        // Authority
        $authority = '';
        if (!empty($defaultInfo['user'])) {
            $authority .= $defaultInfo['user'];
            if (!empty($defaultInfo['pass'])) {
                $authority .= ':' . $defaultInfo['pass'];
            }
        }

        // Host
        if (!empty($defaultInfo['host'])) {
            if (!empty($authority)) {
                $authority .= '@';
            }

            $authority .= $defaultInfo['host'];
        }

        // Port
        if (!empty($defaultInfo['port'])) {
            $authority .= ':' . $defaultInfo['port'];
        }

        if (in_array($defaultInfo['scheme'], $this->hasNoDoubleSlashesSchemes)) {
        } else if (!empty($authority) || in_array($defaultInfo['scheme'], $this->doubleSlashesSchemes)) {
            $uri .= '//';
        }
        $uri .= $authority;

        // Path
        if (!empty($defaultInfo['path'])) {
            if (!empty($authority) && $defaultInfo['path'][0] != '/') {
                $uri .= '/';
            }

            $uri .= $defaultInfo['path'];
        }

        // Query
        if (!empty($defaultInfo['query'])) {
            $uri .= '?' . $defaultInfo['query'];
        }

        // Fragment
        if (!empty($defaultInfo['fragment'])) {
            $uri .= '#' . $defaultInfo['fragment'];
        }

        return $uri;
    }

    /*
     * Parse URI to default info
     *
     * @param string $uri
     *
     * @return array
     */
    private function parseUriToDefaultInfo(string $uri) : array
    {
        $defaultInfo = [
            'scheme' => '',
            'host' => '',
            'port' => '',
            'user' => '',
            'pass' => '',
            'path' => '',
            'query' => '',
            'fragment' => ''
        ];

        // Scheme
        if (preg_match('/^(([a-z][a-z+.-]*):)(\/\/|)(.*)/i', $uri, $matches)) {
            $uri = $matches[4];
            $defaultInfo['scheme'] = $matches[2];
        }

        // Fragment
        if (preg_match('/(.*)#(.+)$/', $uri, $matches)) {
            $defaultInfo['fragment'] = $matches[2];
            $uri = $matches[1];
        }

        // Query
        if (preg_match('/(.*)\?(.+)$/', $uri, $matches)) {
            $defaultInfo['query'] = $matches[2];
            $uri = $matches[1];
        }

        // Path
        if (preg_match('/(.*?)(\/.*)$/', $uri, $matches)) {
            $defaultInfo['path'] = $matches[2];
            $uri = $matches[1];
        }

        // Port
        if (preg_match('/(.*):([0-9]+)$/', $uri, $matches)) {
            $defaultInfo['port'] = $matches[2];
            $uri = $matches[1];
        }

        // User info
        if (preg_match('/(.+?)(:(.+?))?@(.*)/', $uri, $matches)) {
            $defaultInfo['user'] = $matches[1];
            $defaultInfo['pass'] = $matches[3];
            $uri = $matches[4];
        }

        // Host
        $defaultInfo['host'] = $uri;

        return $defaultInfo;
    }

    /**
     * Check if given port is de default port for given scheme
     *
     * @param int    $port
     * @param string $scheme
     *
     * @return bool
     */
    private function isDefaultPort(int $port, string $scheme) : bool
    {
        return isset($this->defaultSchemePorts[$port]) && $this->defaultSchemePorts[$port] == $scheme;
    }

    /**
     * Set scheme
     *
     * @param string $scheme
     *
     * @return $this
     */
    public function setScheme(string $scheme)
    {
        $this->scheme = strtolower($scheme);
        if (($defaultPort = array_search($scheme, $this->defaultSchemePorts)) !== false) {
            $this->port = $defaultPort;
        }

        return $this;
    }

    /**
     * Get scheme
     *
     * @return string
     */
    public function getScheme() : string
    {
        return $this->scheme;
    }

    /**
     * Set host
     *
     * @param HostInterface $host
     *
     * @return $this
     */
    public function setHost(HostInterface $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return HostInterface
     */
    public function getHost() : HostInterface
    {
        return $this->host;
    }

    /**
     * Set port
     *
     * @param int $port
     *
     * @return $this
     */
    public function setPort(int $port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return int
     */
    public function getPort() : int
    {
        return $this->port;
    }

    /**
     * Set user info
     *
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function setUserInfo(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * @return string
     */
    public function getUserInfo() : string
    {
        return $this->getUri(URI_USERINFO);
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string
     */
    public function getAuthority() : string
    {
        return $this->getUri(URI_AUTHORITY);
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Set query
     *
     * @param QueryParametersInterface $queryParameters
     *
     * @return $this
     */
    public function setQuery(QueryParametersInterface $queryParameters)
    {
        $this->queryParameters = $queryParameters;

        return $this;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * @return QueryParametersInterface
     */
    public function getQuery() : QueryParametersInterface
    {
        return $this->queryParameters;
    }

    /**
     * Set fragment
     *
     * @param string $fragment
     *
     * @return $this
     */
    public function setFragment(string $fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Get fragment
     *
     * @return string
     */
    public function getFragment() : string
    {
        return $this->fragment;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     *
     * @return static
     */
    public function withScheme($scheme)
    {
        if ($this->scheme == $scheme) {
            return $this;
        }

        $new = clone $this;
        $new->setScheme($scheme);
        return $new;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     *
     * @return static
     */
    public function withUserInfo($user, $password = null)
    {
        if ($this->username == $user && $this->password == $password) {
            return $this;
        }

        $new = clone $this;
        $new->setUserInfo($user, $password);
        return $new;
    }

    /**
     * Return an instance with the specified host.
     *
     * @param string|HostInterface $host The hostname to use with the new instance.
     *
     * @return static
     */
    public function withHost($host)
    {
        if (is_string($host)) {
            $host = new Host($host);
        }

        if ($this->host == $host) {
            return $this;
        }

        $new = clone $this;
        $new->setHost($host);
        return $new;
    }

    /**
     * Return an instance with the specified port.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     *
     * @return static
     */
    public function withPort($port)
    {
        if ($this->port == $port) {
            return $this;
        }

        $new = clone $this;
        $new->setPort($port);
        return $new;
    }

    /**
     * Return an instance with the specified path.
     *
     * @param string $path The path to use with the new instance.
     *
     * @return static
     */
    public function withPath($path)
    {
        if ($this->path == $path) {
            return $this;
        }

        $new = clone $this;
        $new->setPath($path);
        return $new;
    }

    /**
     * Return an instance with the specified query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @return static
     */
    public function withQuery($query)
    {
        if ($this->getQuery() == $query) {
            return $this;
        }

        $new = clone $this;
        $new->setQuery(new QueryParameters($query));
        return $new;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     *
     * @return static
     */
    public function withFragment($fragment)
    {
        if ($this->fragment == $fragment) {
            return $this;
        }

        $new = clone $this;
        $new->setFragment($fragment);
        return $new;
    }
}

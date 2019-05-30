<?php
declare(strict_types = 1);

namespace Scholte\Uri;

define('URI_STRIP_SUB_DOMAIN', 128);

/**
 * Class Host
 *
 * @package Scholte\Uri
 */
class Host implements HostInterface
{
    /**
     * IP address
     * @var string
     */
    private $ip;

    /**
     * Sub domain
     * @var string
     */
    private $subDomain;

    /**
     * Root domain
     * @var string
     */
    private $rootDomain;

    /**
     * Top level domain
     * @var string
     */
    private $topLevelDomain;

    /**
     * Constructor
     *
     * @param string $host
     */
    public function __construct(string $host = '')
    {
        if (!empty($host)) {
            $this->parse($host);
        }
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->getHost();
    }

    /**
     * Parse host
     *
     * @param string $host
     */
    public function parse(string $host)
    {
        $defaultInfo = [
            'ip' => '',
            'subDomain' => '',
            'rootDomain' => '',
            'topLevelDomain' => ''
        ];


        // IP4 address
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $defaultInfo['ip'] = $host;

        // IP6 address
        } elseif (!empty($host) && $host[0] == '[' && $host[strlen($host) - 1] == ']'
            && filter_var(substr($host, 1, -1), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
        ) {
            $defaultInfo['ip'] = substr($host, 1, -1);

        // Default host
        } elseif (preg_match('/([a-z0-9-]*?)(\.?([^.]+))\.([^.]+)$/', $host, $matches)) {
            $defaultInfo = array_merge(
                $defaultInfo,
                [
                    'subDomain' => $matches[1],
                    'rootDomain' => $matches[3],
                    'topLevelDomain' => $matches[4]
                ]
            );

        // Fallback
        } else {
            $defaultInfo['rootDomain'] = $host;
        }

        $this
            ->setIp($defaultInfo['ip'])
            ->setSubDomain($defaultInfo['subDomain'])
            ->setRootDomain($defaultInfo['rootDomain'])
            ->setTopLevelDomain($defaultInfo['topLevelDomain'])
        ;
    }

    /**
     * Get host
     *
     * @param int $filter
     *
     * @return string
     */
    public function getHost(int $filter = 0) : string
    {
        $host = [];

        // IP address
        if (!empty($this->ip)) {
            if (filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $host = ['[' . $this->ip . ']'];
            } else {
                $host = [$this->ip];
            }
        }

        // Default host
        if (!empty($this->rootDomain) && !empty($this->topLevelDomain)) {
            $host = [];

            if (!($filter & URI_STRIP_SUB_DOMAIN)) {
                $host[] = $this->subDomain;
            }
            $host[] = $this->rootDomain;
            $host[] = $this->topLevelDomain;

        } else {
            $host[] = $this->rootDomain;
        }

        $host = array_filter($host);

        return implode('.', $host);
    }

    /**
     * Set IP
     *
     * @param string $ip
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setIp(string $ip)
    {
        if (!empty($ip) && !filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException('Invalid IP address provided');
        }

        $this->ip = $ip;

        return $this;
    }

    /**
     * Get IP
     *
     * @return string
     */
    public function getIp() : string
    {
        return $this->ip;
    }

    /**
     * Set sub domain
     *
     * @param string $subDomain
     *
     * @return $this
     */
    public function setSubDomain(string $subDomain)
    {
        $this->subDomain = $subDomain;

        return $this;
    }

    /**
     * Get sub domain
     *
     * @return string
     */
    public function getSubDomain() : string
    {
        return $this->subDomain;
    }

    /**
     * Set root domain
     *
     * @param string $rootDomain
     *
     * @return $this
     */
    public function setRootDomain(string $rootDomain)
    {
        $this->rootDomain = $rootDomain;

        return $this;
    }

    /**
     * Get root domain
     *
     * @return string
     */
    public function getRootDomain() : string
    {
        return $this->rootDomain;
    }

    /**
     * Set top level domain
     *
     * @param string $topLevelDomain
     *
     * @return $this
     */
    public function setTopLevelDomain(string $topLevelDomain)
    {
        $this->topLevelDomain = $topLevelDomain;

        return $this;
    }

    /**
     * Get top level domain
     *
     * @return string
     */
    public function getTopLevelDomain() : string
    {
        return $this->topLevelDomain;
    }
}

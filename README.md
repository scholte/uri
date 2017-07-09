# README

This object follows the PSR-7 HTTP message described at [http://www.php-fig.org/psr/psr-7/](http://www.php-fig.org/psr/psr-7/.) and [RFC 3986](https://tools.ietf.org/html/rfc3986).
Every URI has a specific construction as shown below:

```
tel:+31-123-456-789
urn:oasis:names:specification:docbook:dtd:xml:4.1.2
ftp://ftp.is.co.za/rfc/rfc1808.txt
mailto:John.Doe@example.com
scheme:[//[user:password@]host[:port]][/]path[?query][#fragment]
```

1. Protocol: ```scheme```
2. Authentication: ```user:password```
3. Domain: ```www.host.com```
4. Port: ```80```
5. Path: ```/path```
6. Query: ```?parameter=value```
7. Fragment: ```#fragment```

## Installation

This package can be installed using composer:

```bash
$ composer require scholte/uri
```

## How to use

The URI object can be used for all RFC 3986 URI's.
It is possible to change or extract specific parts of the URI without having to search and replace those parts.

```php
<?php
use Scholte\Uri;

$uri = new Uri('scheme://user:password@www.host.com:80/path?parameter=value#fragment');
echo $uri->setScheme('https')->getUri(URI_STRIP_FRAGMENT);
// Output: https://user:password@www.host.com/path?parameter=value

$uri = new Uri('http://www.host.com/path');
echo $uri->getUri(URI_STRIP_SCHEME|URI_STRIP_HOST);
// Output: /path
```

## About

### Dependencies

* Works with PHP 7.1 or higher
* psr/http-message ^1.0

### Author

Christiaan Scholte - <cscholte_83@hotmail.com>

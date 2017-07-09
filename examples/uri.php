<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$rootDir = dirname(dirname(__FILE__));
require_once $rootDir . '/vendor/autoload.php';

function pr($value) { echo '<pre>' . print_r($value, true) . '</pre>'; }
function prx($value) { pr($value); exit; }

use Scholte\Uri;

$uri = new Uri('scheme://www.host.com:80/path?parameter=value#fragment');
$uri->setUserInfo('username', 'password');
pr('URI: ' . $uri);
pr('Authority: ' . $uri->getAuthority());
pr('Port: ' . $uri->getPort());
pr('Path: ' . $uri->getUri(URI_PATH));
pr('Without path: ' . $uri->getUri(URI_STRIP_PATH));
pr($uri);

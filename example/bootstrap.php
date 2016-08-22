<?php

require __DIR__ . '/Application.php';

use T7LC\Soap\Client;
use T7LC\Soap\Cache\FileCache;
use T7LC\Soap\Cache\RedisCache;

session_start();

$app         = new Application();

/**
 * Add the configuration to our container
 */
$app['cfg']  = require '../config/common.php';
$app['cfg']  = array_replace_recursive($app['cfg'], require '../config/secret.php');

/**
 * Add a Closure that always returns a fresh SoapClient instance
 */
$app['soap'] = function() use ($app) {
    //always return a new instance to avoid nasty Segmentation fault bug
    //see https://bugs.php.net/bug.php?id=43437 for example
    return new \SoapClient($app['cfg']['urls']['wsdl']);
};

/**
 * Add a CacheInterface instance depending on configuration
 */
switch($app['cfg']['cache']['type']) {
    case FileCache::getName():
        $app['cache']     = new FileCache($app);
        break;
    case RedisCache::getName():
        $app['cache']     = new RedisCache($app);
        break;
    default:
        throw new \RuntimeException('Unknown or missing cache type: ' . $app['cfg']['cache']['type']);
}

/**
 * Add a closure that shares one instance of the client
 */
$app['t7_client'] = function() use ($app) {

    static $client;

    if (is_null($client)) {
        $client = new Client($app);
    }

    return $client;
};

return $app;
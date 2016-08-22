<?php

//ensure this is false for production environments!
//ini_set( "display_errors", false);

if (ini_get( "display_errors")) {
    set_exception_handler(function (Exception $ex){
        require __DIR__ . '/views/error500.php';
    });
}

if (!is_file('../config/custom.php')) {
    throw new RuntimeException('Please follow instructions inside '.realpath(__DIR__.'/../config/custom.php.dist').'!', 7770);
}

require __DIR__ . '/Application.php';

use T7LC\Soap\Client;
use T7LC\Soap\Cache\FileCache;
use T7LC\Soap\Cache\RedisCache;

session_start();

$app         = new Application();

/**
 * Add configuration to container
 */
$app['cfg']  = require '../config/common.php';
$app['cfg']  = array_replace_recursive($app['cfg'], require '../config/custom.php');

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
        throw new \RuntimeException('Unknown or missing cache type: ' . $app['cfg']['cache']['type'], 7772);
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
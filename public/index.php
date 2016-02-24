<?php

if (!is_file('../config/secret.php')) {
    die('Please follow instructions inside cache/secret.php.dist!');
}

require '../src/autoload.php';

$app = require '../src/bootstrap.php';

/**
 * From this point things get quick and dirty ;)
 */

//TODO dynamically set language
$app['lang']  = 'de';

//default route
$app['route'] = 'cams';

if (isset($_GET['chatOptions'])) {
    $app['route'] = 'chatOptions';
} else if (isset($_GET['chat'])) {
    $app['route'] = 'chat';
} else if (isset($_GET['keepAlive'])) {
    $app['route'] = 'keepAlive';
} else if (isset($_GET['endChat'])) {
    $app['route'] = 'endChat';
} else if (isset($_GET['chatExit'])) {
    $app['route'] = 'chatExit';
}

$routes = require '../src/routes.php';

$routes($app);
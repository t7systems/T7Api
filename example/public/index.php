<?php

if (!is_file('../config/secret.php')) {
    die('Please follow instructions inside cache/secret.php.dist!');
}

require '../../src/autoload.php';

$app = require '../bootstrap.php';

/**
 * From this point things get quick and dirty ;)
 */

//default route
$app['route'] = 'cams';

if (isset($_POST['lang'])) {
    $app['route'] = 'lang';
} else if (isset($_GET['chatOptions'])) {
    $app['route'] = 'chatOptions';
} else if (isset($_GET['chat'])) {
    $app['route'] = 'chat';
} else if (isset($_GET['keepAlive'])) {
    $app['route'] = 'keepAlive';
} else if (isset($_GET['endChat'])) {
    $app['route'] = 'endChat';
} else if (isset($_GET['chatExit'])) {
    $app['route'] = 'chatExit';
} else if (isset($_GET['sedcard'])) {
    $app['route'] = 'sedcard';
}

$app['lang'] = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'de';

$routes = require '../routes.php';

$routes($app);
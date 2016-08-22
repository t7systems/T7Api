<?php

if (!is_file('../config/secret.php')) {
    die('Please follow instructions inside cache/secret.php.dist!');
}

require '../../src/autoload.php';

$app = require '../bootstrap.php';

/**
 * To keep it simple, we use $_GET/$_POST parameters to determine the requested route.
 *
 * Inside the routes closure ('routes.php'), a route is matched against this parameter and
 * a corresponding 'view' is included to render the response body.
 *
 * You may want to use a more sophisticated way to do that (see Symfony et al.)
 */

//default route
$app['route'] = 'cams';

if (isset($_POST['lang'])) {
    $app['route'] = 'lang';
} else if (isset($_POST['livesnap'])) {
    $app['route'] = 'livesnap';
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

$app['lang']     = isset($_SESSION['lang'])     ? $_SESSION['lang']     : 'de';
$app['livesnap'] = isset($_SESSION['livesnap']) ? $_SESSION['livesnap'] : '0';

$routes = require '../routes.php';

$routes($app);
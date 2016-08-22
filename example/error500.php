<!DOCTYPE html>
<html>
<head>
    <title>T7LC API Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF'] ?>">T7LC API DEMO</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <h1>Oops...</h1>
        <h3>There seems to be a problem. Check out the message below:</h3>

        <?php if ($ex instanceof SoapFault): ?>
            <?php if ($ex->faultcode == 'ErrorCode #1001'): ?>
                <p>
                    Please check <samp>reqId</samp> and <samp>secretKey</samp> inside <code><?php echo realpath(__DIR__ . '/../config/custom.php') ?></code>
                </p>
                <p>
                    Please contact us, if the problem persists!
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($ex->getCode() == 7770): ?>
            <p>
                Please copy <code><?php echo realpath(__DIR__ . '/../config/custom.php.dist') ?></code> to
                <code><?php echo str_ireplace('.dist', '',realpath(__DIR__ . '/../config/custom.php.dist'))  ?></code>
            </p>
            <p>
                Open <code><?php echo str_ireplace('.dist', '',realpath(__DIR__ . '/../config/custom.php.dist')) ?></code> with your favourite editor
                and enter valid values for <samp>reqId</samp> and <samp>secretKey</samp>.
            </p>
        <?php endif; ?>

        <?php if ($ex->getCode() == 7771): ?>
            <p>
                PHPREDIS (https://github.com/phpredis/phpredis) was not found on this server.
            </p>
            <p>
                Please ensure that Redis is available or use file caching.
            </p>
            <p>
                <b>Edit <code><?php echo str_ireplace('.dist', '',realpath(__DIR__ . '/../config/custom.php.dist')) ?></code>, to enable file cache.</b>
            </p>
        <?php endif; ?>

        <?php if ($ex->getCode() == 7772): ?>
            <p>
                A cache type MUST be declared inside the configuration file.
            </p>
            <p>
                Open <code><?php echo str_ireplace('.dist', '',realpath(__DIR__ . '/../config/custom.php.dist')) ?></code> with your favourite editor
                and specify a valid cache type.
            </p>
        <?php endif; ?>

        <hr />
        Exception message: <br />
<pre>
<?php echo $ex->getMessage() ?>
</pre>
    </div>
</body>
</html>
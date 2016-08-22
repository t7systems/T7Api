<!DOCTYPE html>
<html>
    <head>
        <title>T7LC API Demo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
        <link href="css/main.css" rel="stylesheet">
    </head>
    <body>
        <div class="container-fluid">
            <?php if (isset($redirect)): ?>
                <a target="_top" href="<?php echo $redirect ?>">You will be redirected shortly. If not, click here...</a>
            <?php else: ?>
                <a target="_top" href="<?php echo $_SERVER['PHP_SELF'] ?>">You will be redirected shortly. If not, click here...</a>
            <?php endif; ?>
            <?php if (isset($chatStatus)): ?>
                <table class="table table-striped">
                    <tr>
                        <th>
                            Start
                        </th>
                        <th>
                            Stop
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $startTime ?>
                        </td>
                        <td>
                            <?php echo $stopTime ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $startTimeUTC ?> (UTC)
                        </td>
                        <td>
                            <?php echo $stopTimeUTC ?> (UTC)
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
        <script>
            //clear timeout to prevent parent window from redirecting elsewhere
            window._T7LC_keepAliveTimeout && clearTimeout(window._T7LC_keepAliveTimeout);

            <?php if (isset($redirect)): //redirect from voyeur to chat ?>
                window.top.location.href = "<?php echo $redirect ?>";
            <?php else: ?>
                //redirect users to overview page or sedcard or...
                //window.top.location.href = "<?php echo $_SERVER['PHP_SELF'] ?>";
            <?php endif; ?>
        </script>
    </body>
</html>
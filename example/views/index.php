<?php if (!$app->isAjax()): ?>
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
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <?php if (isset($category)): ?>
              <li <?php if ($category == null): ?>class="active"<?php endif; ?>>
                <a title="<?php echo $cat->catDescription ?>" href="<?php echo $_SERVER['PHP_SELF'] ?>">
                  All <?php if ($category == null): ?><span class="sr-only">(current)</span><?php endif; ?>
                </a>
              </li>
              <?php foreach($categories as $cat): ?>
                <li <?php if ($cat->catID == $category): ?>class="active"<?php endif; ?>>
                  <a title="<?php echo $cat->catDescription ?>" href="?cat=<?php echo $cat->catID ?>">
                    <?php echo $cat->catName ?> <?php if ($cat->catID == $category): ?><span class="sr-only">(current)</span><?php endif; ?>
                  </a>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
          <form method="post" class="navbar-form navbar-right">
            <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
            <select class="form-control" name="lang" onchange="this.form.submit()">
              <option value="de" <?php if ($app['lang'] == 'de'): ?>selected="selected"<?php endif; ?>>DE</option>
              <option value="en" <?php if ($app['lang'] == 'en'): ?>selected="selected"<?php endif; ?>>EN</option>
            </select>
          </form>
          <form method="post" class="navbar-form navbar-right">
            <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
            <select class="form-control" name="livesnap" onchange="this.form.submit()">
              <option value="0" <?php if ($app['livesnap'] == '0'): ?>selected="selected"<?php endif; ?>>Preview Pics</option>
              <option value="1" <?php if ($app['livesnap'] == '1'): ?>selected="selected"<?php endif; ?>>Live Snapshots</option>
            </select>
          </form>
        </div>
      </div>
    </nav>
<?php endif; ?>

    <?php if ($app['route'] == 'sedcard') : ?>
      <?php include 'sedcard.php' ?>
    <?php elseif ($app['route'] == 'chatOptions') : ?>
      <?php include 'chat_options.php' ?>
    <?php elseif ($app['route'] == 'chat') : ?>
      <?php include 'chat.php' ?>
    <?php elseif ($app['route'] == 'cams') : ?>
      <?php include 'cams.php' ?>
    <?php elseif ($app['route'] == 'offline') : ?>
      <?php include 'offline.php' ?>
    <?php endif; ?>

<?php if (!$app->isAjax()): ?>
    <div id="chat-options-modal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">

        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>

    <script>
      $(function() {
        //reload options form every time modal is shown
        $('#chat-options-modal').on('show.bs.modal', function (event) {
          var link = $(event.relatedTarget);
          $(this).find('.modal-content').html('loading...');
          $(this).find('.modal-content').load(link.attr('href'));
        })
      });
    </script>

  </body>
</html>
<?php endif; ?>
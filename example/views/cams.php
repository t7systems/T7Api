<div class="container-fluid">
    <?php foreach ($cams as $cam) : ?>
        <div class="col-xs-6 col-sm-4 col-md-2 cambox">
            <h4><?= $cam->camName ?></h4>
            <div class="image">
                <a href="?sedcard=<?php echo $cam->camID ?>">
                    <?php if ($app['livesnap'] == 1): ?>
                        <img src="<?php echo $livePreviews[$cam->camID] ?>" />
                    <?php else: ?>
                        <img src="<?php echo $cam->prevPicURLs[2] ?>" alt="<?php echo $cam->camName ?>" title="<?php echo $cam->camName ?>">
                    <?php endif; ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="container-fluid">
    <?php foreach ($cams as $cam) : ?>
        <div class="col-xs-6 col-sm-4 col-md-2 cambox">
            <h4><?= $cam->camName ?></h4>
            <div class="image">
                <a href="?chatOptions=<?php echo $cam->camID ?>" data-toggle="modal" data-target="#chat-options-modal">
                    <img src="<?php echo $cam->prevPicURLs[2] ?>" alt="<?php echo $cam->camName ?>" title="<?php echo $cam->camName ?>">
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
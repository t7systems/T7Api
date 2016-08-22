<div class="container-fluid sedcard">


    <div class="col-xs-12 hidden-lg hidden-md">
        <h3><?php echo $sedcard->camName ?></h3>
    </div>

    <div class="col-xs-12">
        <p class="clearfix">
            <a class="btn btn-success col-xs-12" href="?chatOptions=<?php echo $sedcard->camID ?>" data-toggle="modal" data-target="#chat-options-modal">
                ENTER CHAT
            </a>
        </p>
    </div>

    <?php
    /**
     * You can use calculated aspect ratios, if you want to
     */
    ?>

    <style>
        .video-container:after {
            padding-top: <?php echo ($video->vidHeight/$video->vidWidth*100) ?>%!important;
        }
    </style>

    <?php if ($video != null): ?>
        <div class="col-xs-12 col-md-6">
            <div class="video-container aspect-16-9">
                <video controls preload="none" poster="<?php echo $app['cfg']['urls']['content'] ?>?reqid=<?php echo $app['cfg']['reqId'] ?>&ctype=4&cid=<?php echo $video->vidPrevPics[0] ?>&size=org">
                    <source src="<?php echo $video->vidMP4URL ?>" type="video/mp4" />
                </video>
            </div>
            <?php foreach ($pics as $pic): ?>
                <div class="col-xs-4 cambox">
                    <div class="image">
                        <img src="<?php echo $pic ?>" />
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="col-xs-12 col-md-6">
            <div class="image" id="img-big">
                <img src="<?php echo $pics[0] ?>" />
            </div>
            <?php foreach ($pics as $pic): ?>
                <div class="col-xs-4 cambox">
                    <div class="image">
                        <img src="<?php echo $pic ?>" />
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="col-xs-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading hidden-xs hidden-sm">
                <h1 class="panel-title"><?php echo $sedcard->camName ?></h1>
            </div>
            <div class="panel-body">
                <?php foreach ($sedcard as $key => $value): ?>
                    <div class="col-xs-6">
                        <?php echo $key ?>
                    </div>
                    <div class="col-xs-6">
                        <?php if (is_array($value)): ?>
                            <?php echo implode(', ', $value) ?>
                        <?php elseif (is_bool($value)): ?>
                            <?php echo $value ? 1 : 0 ?>
                        <?php elseif (empty($value)): ?>
                            n/a
                        <?php else: ?>
                            <?php echo $value ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <p class="clearfix">
            <a class="btn btn-success col-xs-12" href="?chatOptions=<?php echo $sedcard->camID ?>" data-toggle="modal" data-target="#chat-options-modal">
                ENTER CHAT
            </a>
        </p>
    </div>
</div>

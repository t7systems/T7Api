<form method="get" action="">
    <input type="hidden" name="chat" value="<?php echo $camId ?>" />
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Enter Chat</h4>
    </div>
    <div class="modal-body">
        <p>Please set some options&hellip;</p>
        <div class="form-group">
            <input class="form-control" type="text" name="nickname" value="<?php echo $nickname ?>" placeholder="Nickname" pattern="[A-Za-z0-9]{3,10}" required="required" />
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="voyeurMode" value="1" <?php if($voyeurMode): ?>checked="checked"<?php endif; ?> />
                Enter as voyeur
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="showcam2cam" value="1" <?php if($showCam2Cam): ?>checked="checked"<?php endif; ?> />
                Show Cam2Cam buttons
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="sendsound" value="1" <?php if($sendSound): ?>checked="checked"<?php endif; ?> />
                Allow audio
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="showsendsound" value="1" <?php if($showSendSound): ?>checked="checked"<?php endif; ?> />
                Show audio buttons
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Enter chat now!</button>
    </div>
</form>
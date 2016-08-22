<button id="exit-button" class="btn btn-danger" onclick="chatExit();">EXIT</button>

<iframe id="chatframe" name="chatframe" src="<?php echo $chatUrl ?>" allowfullscreen></iframe>

<script>
    //ask server to extend the chat session
    var keepAliveTimeout = null;
    var keepAlive        = function() {
        $.get("<?php echo $_SERVER['PHP_SELF'] . "?keepAlive" ?>", function( data ) {
            if (data != 'ok') {
                //alert( "Chat session expired!" );
                window.frames['chatframe'].location.href = "<?php echo $app['cfg']['quitUrl'] ?>";
            } else {
                keepAliveTimeout = setTimeout(keepAlive, 5000);
            }
        });
    };
    keepAliveTimeout = setTimeout(keepAlive, 5000);

    //end chat session and redirect to exit page
    var chatExit = function() {
        //stop keep alive requests
        clearTimeout(keepAliveTimeout);
        $ && $.get(
            '<?php echo $_SERVER['PHP_SELF'] . "?endChat" ?>',
            function( data ) {
                window.frames['chatframe'].location.href ='<?php echo $app['cfg']['quitUrl'] ?>';
            }
        );
    };
</script>
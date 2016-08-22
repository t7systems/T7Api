<button id="exit-button" class="btn btn-danger" onclick="chatExit();">EXIT</button>

<iframe id="chatframe" name="chatframe" src="<?php echo $chatUrl ?>" allowfullscreen></iframe>

<script>
    //ask server to extend the chat session

    //declare timer globally, so we're able to clear it from inside the iframe (after a redirect)
    window._T7LC_keepAliveTimeout = null;
    var keepAlive        = function() {
        $.get("<?php echo $_SERVER['PHP_SELF'] . "?keepAlive" ?>", function( data ) {
            if (data != 'ok') {
                window.frames['chatframe'].location.href = "<?php echo $app['cfg']['quitUrl'] ?>";
            } else {
                window._T7LC_keepAliveTimeout = setTimeout(keepAlive, 5000);
            }
        });
    };
    window._T7LC_keepAliveTimeout = setTimeout(keepAlive, 5000);

    //end chat session and redirect to exit page
    var chatExit = function() {
        //stop keep alive requests
        clearTimeout(window._T7LC_keepAliveTimeout);
        $ && $.get(
            '<?php echo $_SERVER['PHP_SELF'] . "?endChat" ?>',
            function( data ) {
                window.frames['chatframe'].location.href ='<?php echo $app['cfg']['quitUrl'] ?>';
            }
        );
    };
</script>
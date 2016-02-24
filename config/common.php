<?php
return array(

    /**
     * Mandatory options for the API (see also: 'secret.php'):
     */
    'urls'             => array(
        'wsdl'    => 'http://content.777live.com/soap/1_4/777live.wsdl',
        'content' => 'http://content.777live.com/soap/1_4/getcontent.php',
    ),

    //Chat sessions are created/extended with the following ttl in seconds (should be 10 as a minimum)
    'seconds'          => '10',

    //Callback URL for ended chat sessions. Users will be redirected to this URL.
    'quitUrl'          => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?chatExit',

    /**
     * Application specific options:
     */
    'cache'            => array(
        //time in seconds until cache is invalid
        'ttl' => array(
            'categories' => 3600,
            'cams'       => 60,
            'sedcards'   => 3600,
        ),

        //'file', 'redis'
        'type'         => \T7\Cache\RedisCache::getName(),

        //FileCache:
        //A writable directory to store API data
        'dir'          => __DIR__ . '/../cache',

        //RedisCache (for credentials, see secret.php)
        'redis_host'   => '127.0.0.1',
        'redis_port'   => null, //use default
        'redis_db'     => 0,
        'redis_prefix' => 't7api_cache'
    ),
);
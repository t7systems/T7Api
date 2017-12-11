<?php

namespace T7LC\Soap\Provider;

use T7LC\Soap\Cache\FileCache;
use T7LC\Soap\Client;

/**
 * Class ClientServiceProvider
 * Basic service provider for use with ArrayAccess Container
 * @package T7LC\Soap\Provider
 */
class ClientServiceProvider
{
    /**
     * Registers a shared Client instance inside the container
     * @param \ArrayAccess $container
     */
    public function register(\ArrayAccess $container)
    {
        $container['t7_client'] = function() use ($container) {

            static $client;

            if (is_null($client)) {

                if (!isset($container['cache'])) {
                    $container['cache'] = new FileCache();
                }


                if (!isset($container['soap'])) {
                    $container['soap'] = function () use ($container) {
                        //Always return a new instance to avoid nasty Segmentation fault bug.
                        //See https://bugs.php.net/bug.php?id=43437 for example
                        //If you feel lucky, share a singleton instance (see $app['t7_client'] below)

                        $wsdl = 'https://content.777live.com/soap/1_4/777live.wsdl';
                        if (isset($container['cfg']) && isset($container['cfg']['urls']) && isset($container['cfg']['urls']['wsdl'])) {
                            $wsdl = $container['cfg']['urls']['wsdl'];
                        }

                        //avoid PHP SOAP user_agent bug
                        $opts = array(
                            'http' => array(
                                'user_agent' => 'PHPSoapClient'
                            )
                        );
                        $context = stream_context_create($opts);
                        $soapClientOptions = array(
                            'stream_context' => $context,
                            'cache_wsdl'     => WSDL_CACHE_NONE
                        );

                        return new \SoapClient($wsdl, $soapClientOptions);
                    };
                }

                if (!isset($container['cfg'])) {
                    //won't work with these default values, just avoid notices...
                    $container['cfg'] = array(
                        'reqId' => '0',
                        'secretKey' => '',
                    );
                }

                $client = new Client($container['soap'], $container['cache'], $container['cfg']);
            }

            return $client;
        };
    }
}
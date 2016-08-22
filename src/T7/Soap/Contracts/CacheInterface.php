<?php

namespace T7\Soap\Contracts;

/**
 * Interface CacheInterface
 * Basic interface for Cache operations (get/set)
 * @package T7\Cache
 */
interface CacheInterface
{
    /**
     * @return string The name of this cache implementation
     */
    public static function getName();

    /**
     * @param string $key
     * @return array (data,time)
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value A serializable value
     * @param int $ttl Number of seconds until the cache expires (Default: forever)
     * @return void
     */
    public function set($key, $value, $ttl = 0);

}
<?php

namespace T7LC\Soap\Cache;

use T7LC\Soap\Contracts\CacheInterface;

/**
 * Class RedisCache
 * Caches data to redis server
 * @package T7LC\Soap\Cache
 */
class RedisCache implements CacheInterface
{

    /**
     * @var \Redis
     */
    protected $redis;

    public static function getName()
    {
        return 'redis';
    }

    public function __construct(array $options = array())
    {
        if (false == class_exists('Redis', false)) {
            throw new \RuntimeException('phpredis extension not found. Please visit https://github.com/phpredis/phpredis', 7771);
        }

        $defaults = array(
            'redis_host'   => '127.0.0.1',
            'redis_port'   => null,
            'redis_db'     => 0,
            'redis_prefix' => 't7api_cache',
            'redis_pass'   => '',
        );
        $options = array_replace_recursive($defaults, $options);

        $this->redis = new \Redis();
        $this->redis->connect($options['redis_host'], $options['redis_port']);

        if (isset($options['redis_pass']) && false == empty($options['redis_pass'])) {
            if (false == $this->redis->auth($options['redis_pass'])) {
                throw new \RuntimeException('Redis auth failed!');
            }
        }

        $this->redis->select($options['redis_db']);
        $this->redis->setOption(\Redis::OPT_PREFIX, $options['redis_prefix']);

    }

    /**
     * @param string $key
     * @return array (data,time)
     */
    public function get($key)
    {
        $data  = $this->redis->get($key);
        $time  = 0;
        if ($data) {
            $time = $this->redis->ttl($key);
            $data = unserialize($data);
        }

        return compact('data', 'time');
    }

    /**
     * @param string $key
     * @param mixed $value A serializable value
     * @param int $ttl Number of seconds until the cache expires (Default: forever)
     * @return void
     */
    public function set($key, $value, $ttl = 0)
    {
        $this->redis->set($key, serialize($value), Array('nx', 'ex'=>$ttl));
    }
}
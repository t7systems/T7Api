<?php

namespace T7LC\Soap\Cache;

use ArrayAccess;
use T7LC\Soap\Contracts\CacheInterface;

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

    public function __construct(ArrayAccess $app)
    {
        if (false == class_exists('Redis', false)) {
            throw new \RuntimeException('phpredis extension not found. Please visit https://github.com/phpredis/phpredis', 7771);
        }
        $this->redis = new \Redis();
        $this->redis->connect($app['cfg']['cache']['redis_host'], $app['cfg']['cache']['redis_port']);

        if (false == empty($app['cfg']['cache']['redis_pass'])) {
            if (false == $this->redis->auth($app['cfg']['cache']['redis_pass'])) {
                throw new \RuntimeException('Redis auth failed!');
            }
        }

        $this->redis->select($app['cfg']['cache']['redis_db']);
        $this->redis->setOption(\Redis::OPT_PREFIX, $app['cfg']['cache']['redis_prefix']);

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
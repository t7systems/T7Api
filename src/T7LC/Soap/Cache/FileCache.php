<?php

namespace T7LC\Soap\Cache;


use ArrayAccess;
use T7LC\Soap\Contracts\CacheInterface;

class FileCache implements CacheInterface
{

    /**
     * @var ArrayAccess
     */
    private $app;

    public static function getName()
    {
        return 'file';
    }

    public function __construct(ArrayAccess $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $key
     * @return array
     */
    public function get($key)
    {
        $file = $this->path($key);

        if (is_file($file)) {
            $contents = file_get_contents($file);
            //TODO revisit before 2286-11-20!!
            $expire   = substr($contents, 0, 10);
        } else {
            return array('data' => null, 'time' => null);
        }

        if (time() >= $expire)
        {
            unlink($file);

            return array('data' => null, 'time' => null);
        }

        $data = unserialize(substr($contents, 10));
        $time = ceil(($expire - time()) / 60);

        return compact('data', 'time');
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     */
    public function set($key, $value, $ttl = 0)
    {
        if ($ttl === 0) {
            $timestamp = 9999999999;
        } else {
            $timestamp = time() + $ttl;
        }

        $file = $this->path($key);

        @mkdir(dirname($file), 0777, true);

        if (!is_dir(dirname($file))) {
            throw new \RuntimeException('Please create directory "'.$this->app['cfg']['cache']['dir'].'" and make it writable');
        }

        file_put_contents($file, $timestamp.serialize($value));
    }

    public function flush()
    {
        $cacheDir = $this->app['cfg']['cache']['dir'];

        $this->delTree($cacheDir);

    }

    /**
     * @param $key
     * @return string
     */
    protected function path($key)
    {
        $parts = array_slice(str_split($hash = md5($key), 2), 0, 2);

        return $this->app['cfg']['cache']['dir'].'/'.implode('/', $parts).'/'.$hash;
    }

    protected function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
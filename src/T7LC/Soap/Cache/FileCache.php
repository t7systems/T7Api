<?php

namespace T7LC\Soap\Cache;

use T7LC\Soap\Contracts\CacheInterface;

/**
 * Class FileCache
 * Caches data to file system
 * @package T7LC\Soap\Cache
 */
class FileCache implements CacheInterface
{

    private $options;

    public static function getName()
    {
        return 'file';
    }

    public function __construct(array $options = array())
    {
        $this->options = $options;

        if (!isset($this->options['dir'])) {
            $this->options['dir'] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 't7api_cache';
        }
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
            throw new \RuntimeException('Please create directory "'.$this->options['dir'].'" and make it writable');
        }

        file_put_contents($file, $timestamp.serialize($value));
    }

    public function flush()
    {
        $cacheDir = $this->options['dir'];

        $this->delTree($cacheDir);

    }

    /**
     * @param $key
     * @return string
     */
    protected function path($key)
    {
        $parts = array_slice(str_split($hash = md5($key), 2), 0, 2);

        return $this->options['dir'].'/'.implode('/', $parts).'/'.$hash;
    }

    protected function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
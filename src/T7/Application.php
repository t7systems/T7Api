<?php

namespace T7;

use ArrayAccess;

/**
 * Class Application
 * A very simple dependency injection container.
 * @package T7
 */
class Application implements ArrayAccess
{

    private $container = array();

    /**
     * @return bool
     *
     * //TODO refactor into a request object, helper class or something like that
     */
    public function isAjax() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        $dependency = $this->container[$offset];

        if ($dependency instanceof \Closure) {
            return $dependency();
        }

        return $dependency;
    }

    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}
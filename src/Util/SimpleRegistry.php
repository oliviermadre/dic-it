<?php

namespace Pyrite\DI\Util;

class SimpleRegistry implements Registry
{
    protected $data = array();

    function get($id)
    {
        if($this->has($id)) {
            return $this->data[$id];
        }

        return null;
    }

    function set($id, $value)
    {
        $this->data[$id] = $value;
        return $this;
    }

    function has($id)
    {
        return array_key_exists($id, $this->data);
    }
}
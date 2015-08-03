<?php

namespace DICIT\Generator;

class Registry
{
    protected $array = array();

    public function set($key, &$obj)
    {
        $this->array[$key] = &$obj;
        return true;
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return $this->array[$key];
        }

        return null;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->array);
    }
}

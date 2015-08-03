<?php

namespace DICIT\Generator;

class NameGeneratorUtil
{
    public function getter($key)
    {
        $name = str_replace("-", "", $key);
        return 'get' . $name;
    }
}
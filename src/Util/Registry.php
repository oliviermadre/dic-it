<?php

namespace Pyrite\DI\Util;


interface Registry
{
    function get($id);
    function set($id, $value);
    function has($id);
}
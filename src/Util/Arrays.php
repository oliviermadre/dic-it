<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 03/05/15
 * Time: 03:38
 */

namespace Pyrite\DI\Util;


class Arrays
{
    public static function merge(array $array1 = array(), array $array2 = array())
    {
        $merged = $array1;

        foreach ($array2 as $key => $value)
        {
            if (is_array($value) && isset($merged[$key]) && is_array($merged [$key]))
            {
                $merged[$key] = self::merge($merged[$key], $value);
            }
            else
            {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
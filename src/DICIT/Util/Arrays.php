<?php
namespace DICIT\Util;

class Arrays
{
    public static function mergeRecursiveUnique(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged [$key])) {
                $merged[$key] = self::mergeRecursiveUnique($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

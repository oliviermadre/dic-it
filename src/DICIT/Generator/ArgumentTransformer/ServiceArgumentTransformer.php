<?php

namespace DICIT\Generator\ArgumentTransformer;

class ServiceArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_string($argument) && strlen($argument) && $argument{0} === "@";
    }

    public function transform($argument)
    {
        $methodName = 'get' . str_replace('-', '', substr($argument, 1));
        return '$container->' . $methodName . '()';
    }

    public function isPHPCode()
    {
        return true;
    }
}

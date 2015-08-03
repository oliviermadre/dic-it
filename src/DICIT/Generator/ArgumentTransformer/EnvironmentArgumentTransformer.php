<?php

namespace DICIT\Generator\ArgumentTransformer;

class EnvironmentArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_string($argument) && strlen($argument) && substr($argument, 0, 4) === '$env';
    }

    public function transform($argument)
    {
        $env = substr($argument, 5);
        return 'getEnv("' . $env . '")';
    }

    public function isPHPCode()
    {
        return true;
    }
}

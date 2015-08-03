<?php

namespace DICIT\Generator\ArgumentTransformer;

class ContainerArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_string($argument) && strlen($argument) && $argument === '$container';
    }

    public function transform($argument)
    {
        return '$container';
    }

    public function isPHPCode()
    {
        return true;
    }
}

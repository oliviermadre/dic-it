<?php

namespace DICIT\Generator\ArgumentTransformer;

class EmptyStringArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_string($argument) && !strlen($argument);
    }

    public function transform($argument)
    {
        return '""';
    }

    public function isPHPCode()
    {
        return true;
    }
}

<?php

namespace DICIT\Generator\ArgumentTransformer;

class ScalarArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_scalar($argument);
    }

    public function transform($argument)
    {
        return $argument;
    }

    public function isPHPCode()
    {
        return false;
    }
}

<?php

namespace DICIT\Generator\ArgumentTransformer;

class ConstantArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_string($argument) && strlen($argument) && substr($argument, 0, 6) === '$const';
    }

    public function transform($argument)
    {
        $constant = substr($argument, 7);
        return 'constant("' . $constant . '")';
    }

    public function isPHPCode()
    {
        return true;
    }
}

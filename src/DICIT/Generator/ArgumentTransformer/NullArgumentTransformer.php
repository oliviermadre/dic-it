<?php

namespace DICIT\Generator\ArgumentTransformer;

class NullArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_null($argument);
    }

    public function transform($argument)
    {
        return 'NULL';
    }

    public function isPHPCode()
    {
        return true;
    }
}

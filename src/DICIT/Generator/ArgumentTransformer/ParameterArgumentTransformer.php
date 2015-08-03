<?php

namespace DICIT\Generator\ArgumentTransformer;

class ParameterArgumentTransformer implements ArgumentTransformer
{
    public function canTransform($argument)
    {
        return is_string($argument) && strlen($argument) && $argument{0} === "%";
    }

    public function transform($argument)
    {
        return '$container->getParameter("' . substr($argument, 1) . '")';
    }

    public function isPHPCode()
    {
        return true;
    }
}

<?php

namespace DICIT\Generator\ArgumentTransformer;

interface ArgumentTransformer
{
    public function canTransform($argument);
    public function transform($argument);
    public function isPHPCode();
}

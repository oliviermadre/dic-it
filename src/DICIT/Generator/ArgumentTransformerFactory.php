<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 19/06/15
 * Time: 19:20
 */

namespace DICIT\Generator;

use DICIT\Generator\ArgumentTransformer\ArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ArrayArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ConstantArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ContainerArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\EmptyStringArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\EnvironmentArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\NullArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ParameterArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ScalarArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ServiceArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ServiceLazyArgumentTransformer;
use RuntimeException;

class ArgumentTransformerFactory
{
    protected $transformers = array();

    public function addArgumentTransformer(ArgumentTransformer $transformer)
    {
        $this->transformers[] = $transformer;
        return $this;
    }

    public function getArgumentTransformer($argument)
    {
        /** @var ArgumentTransformer $transformer */
        foreach ($this->transformers as $transformer) {
            if ($transformer->canTransform($argument)) {
                return $transformer;
            }
        }

        throw new RuntimeException("Couldn't find a matching ArgumentTransformer");
    }

    public function transformOne($argument)
    {
        $transformer = $this->getArgumentTransformer($argument);
        $transformedArg = $transformer->transform($argument);
        if ($transformer->isPHPCode()) {
            return array($transformedArg, $transformer);
        }
        return array(var_export($transformedArg, true), $transformer);
    }

    public function transformMany(array $arguments = array())
    {
        $transformedArgs = array();
        foreach ($arguments as $argument) {
            list($v, ) = $this->transformOne($argument);
            $transformedArgs[] = $v;
        }
        return implode(", ", $transformedArgs);
    }
}
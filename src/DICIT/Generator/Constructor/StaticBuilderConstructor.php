<?php

namespace DICIT\Generator\Constructor;

use DICIT\Generator\ArgumentTransformerFactory;

class StaticBuilderConstructor implements Constructor
{
    private $argumentTransformerFactory;

    public function __construct(ArgumentTransformerFactory $factory)
    {
        $this->argumentTransformerFactory = $factory;
    }

    public function construct($serviceName, $serviceConfig)
    {
        $builder = $serviceConfig['builder'];
        $call = '\\' . ltrim($builder, '\\') ;

        $arguments = array_key_exists('arguments', $serviceConfig) ? $serviceConfig['arguments'] : array();
        $argumentsAsString = $this->argumentTransformerFactory->transformMany($arguments);

        return <<<PHP
\$instance = $call($argumentsAsString);


PHP;

    }

    public function canConstruct($serviceConfig)
    {
        return array_key_exists('builder', $serviceConfig) && strpos($serviceConfig['builder'], '::') !== false;
    }
}
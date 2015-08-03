<?php

namespace DICIT\Generator\Constructor;

use DICIT\Generator\ArgumentTransformerFactory;

class DefaultConstructor implements Constructor
{
    private $argumentTransformerFactory;

    public function __construct(ArgumentTransformerFactory $factory)
    {
        $this->argumentTransformerFactory = $factory;
    }

    public function construct($serviceName, $serviceConfig)
    {
        $arguments = array_key_exists('arguments', $serviceConfig) ? $serviceConfig['arguments'] : array();
        if (!is_array($arguments)) {
            $arguments = array($arguments);
        }

        $argumentsAsString = $this->argumentTransformerFactory->transformMany($arguments);

        $className = '\\' . ltrim($serviceConfig['class'], '\\') ;

        return '$instance = new ' . $className . '(' . $argumentsAsString . ');' . "\n";
    }

    public function canConstruct($serviceConfig)
    {
        return array_key_exists('class', $serviceConfig);
    }
}
<?php

namespace DICIT\Generator\Modifier;

use DICIT\Generator\ArgumentTransformerFactory;

class PropertyCallModifier implements Modifier
{
    private $argumentTransformerFactory;

    public function __construct(ArgumentTransformerFactory $factory)
    {
        $this->argumentTransformerFactory = $factory;
    }

    public function canModify(array $serviceConfig = array())
    {
        return array_key_exists('props', $serviceConfig) && count($serviceConfig['props']);
    }

    public function modify($serviceName, array $serviceConfig = array())
    {
        $code = array();
        foreach ($serviceConfig['props'] as $propertyName => $arguments) {
            $argumentsAsString = $this->argumentTransformerFactory->transformMany($arguments);
            $code[] .= <<<PHP
\$instance->$propertyName = $argumentsAsString;
PHP;

        }

        return implode("\n", $code) . "\n";
    }
}
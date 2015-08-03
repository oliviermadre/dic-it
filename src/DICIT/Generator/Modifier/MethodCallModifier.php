<?php

namespace DICIT\Generator\Modifier;

use DICIT\Generator\ArgumentTransformerFactory;

class MethodCallModifier implements Modifier
{
    private $argumentTransformerFactory;

    public function __construct(ArgumentTransformerFactory $factory)
    {
        $this->argumentTransformerFactory = $factory;
    }

    public function canModify(array $serviceConfig = array())
    {
        return array_key_exists('call', $serviceConfig) && count($serviceConfig['call']);
    }

    public function modify($serviceName, array $serviceConfig = array())
    {
        $code = array();
        foreach ($serviceConfig['call'] as $methodKey => $arguments) {
            $expl = explode('[', $methodKey);
            $methodName = $expl[0];
            $argumentsAsString = $this->argumentTransformerFactory->transformMany($arguments);
            $code[] .= <<<PHP
\$instance->$methodName($argumentsAsString);
PHP;

        }

        return implode("\n", $code) . "\n";
    }
}
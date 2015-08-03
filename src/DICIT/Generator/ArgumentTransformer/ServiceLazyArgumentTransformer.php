<?php

namespace DICIT\Generator\ArgumentTransformer;

use DICIT\Config\AbstractConfig;

class ServiceLazyArgumentTransformer implements ArgumentTransformer
{
    protected $config;

    public function __construct(AbstractConfig $config)
    {
        $this->config = $config;
        $data = $config->load();
        $this->serviceData = array_key_exists('classes', $data) ? $data['classes'] : array();
    }

    public function canTransform($argument)
    {
        return is_string($argument) && strlen($argument) && $argument{0} === "~";
    }

    public function transform($argument)
    {
        $serviceName = substr($argument, 1);
        $className = $this->getImplementClass($serviceName);
        $isSingleton = $this->isSingleton($serviceName);

        $methodName = 'getLazyInstance';

        $serviceNameEscaped = var_export($serviceName, true);
        $classNameEscaped = var_export($className, true);
        $isSingletonEscaped = $isSingleton ? 'true' : 'false';

        $code = <<<PHP
\$container->$methodName($serviceNameEscaped, $classNameEscaped, $isSingletonEscaped)
PHP;
        return $code;
    }

    public function isPHPCode()
    {
        return true;
    }

    private function getImplementClass($serviceName)
    {
        $configuration = $this->getServiceConfig($serviceName);

        if (array_key_exists('class', $configuration)) {
            return $configuration['class'];
        }

        throw new \RuntimeException(
            sprintf("Couldn't find the key 'class' for lazy instance of requested \"%s\"", $serviceName)
        );
    }

    private function isSingleton($serviceName)
    {
        $configuration = $this->getServiceConfig($serviceName);

        return array_key_exists('singleton', $configuration) && (bool)$configuration['singleton'];
    }

    private function getServiceConfig($serviceName)
    {
        if (array_key_exists($serviceName, $this->serviceData)) {
            return $this->serviceData[$serviceName];
        }

        throw new \RuntimeException(
            sprintf("Couldn't find the service configuration for the lazy instance of requested \"%s\"", $serviceName)
        );
    }
}

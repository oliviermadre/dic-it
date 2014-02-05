<?php
namespace DICIT\Activators;

use DICIT\Activator;
use DICIT\Container;
use DICIT\UnbuildableServiceException;

class DefaultActivator implements Activator
{

    public function createInstance(Container $container, $serviceName, array $serviceConfig)
    {
        $className = $serviceConfig['class'];

        if (! class_exists($className)) {
            throw new UnbuildableServiceException(sprintf("Class '%s' not found.", $className));
        }

        $class = new \ReflectionClass($className);
        $activationArgs = isset($serviceConfig['arguments']) ? $container->map($serviceConfig['arguments']) : array();

        if (! empty($activationArgs)) {
            $instance = $class->newInstanceArgs($activationArgs);
        }
        else {
            $instance = $class->newInstance();
        }

        return $instance;
    }

}
<?php
namespace DICIT\Activators;

use DICIT\Activator;
use DICIT\Container;

class DefaultActivator implements Activator
{

    public function createInstance(Container $container, $serviceName, array $serviceConfig)
    {
        $class = new \ReflectionClass($serviceConfig['class']);
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
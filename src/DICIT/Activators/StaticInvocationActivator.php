<?php
namespace DICIT\Activators;

use DICIT\Activator;
use DICIT\Container;
use DICIT\UnbuildableServiceException;

class StaticInvocationActivator implements Activator
{

    public function createInstance(Container $container, $serviceName, array $serviceConfig) {
        list($className, $methodName) = explode('::', $serviceConfig['builder']);

        $activationArgs = isset($serviceConfig['arguments']) ? $container->resolveMany($serviceConfig['arguments']) : array();

        if (! class_exists($className)) {
            throw new UnbuildableServiceException(sprintf("Class '%s' not found.", $className));
        } elseif (! method_exists($className, $methodName)) {
            throw new UnbuildableServiceException(sprintf("Class '%s' has no '%s' method.", $className, $methodName));
        }

        return call_user_func_array(array($className, $methodName), $activationArgs);
    }
}

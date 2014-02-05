<?php
namespace DICIT\Activators;

use DICIT\Activator;
use DICIT\Container;
use DICIT\UnbuildableServiceException;

class InstanceInvocationActivator implements Activator
{

    public function createInstance(Container $container, $serviceName, array $serviceConfig) {
        list($instanceName, $methodName) = explode('->', $serviceConfig['builder']);

        $invocationSite = $container->get($instanceName);

        if (! method_exists($invocationSite, $methodName)) {
            throw new UnbuildableServiceException(sprintf("Class '%s' has no '%s' method.", $className, $methodName));
        }

        return call_user_func_array(array($invocationSite, $methodName), $container->map($serviceConfig['arguments']));
    }

}
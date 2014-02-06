<?php

namespace DICIT\Injectors;

use DICIT\Injector;
use DICIT\Container;

class MethodInjector implements Injector
{

    public function inject(Container $container, $service, array $serviceConfig) {
        $callConfig = array();
        
        if (array_key_exists('call', $serviceConfig)) {
            $callConfig = $serviceConfig['call'];
        }
        
        foreach($callConfig as $methodName => $parameters) {
            $convertedParameters = $container->resolveMany($parameters);
            call_user_func_array(array($service, $methodName), $convertedParameters);
        }
        
        return true;
    }
}
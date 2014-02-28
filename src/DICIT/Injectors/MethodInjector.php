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
            if (false !== strpos($methodName, '[')) {
                if (preg_match('`^([^\[]*)\[[0-9]*\]$`i', $methodName, $matches)) {
                    $methodToCall = $matches[1];
                }
                else {
                    throw new \RuntimeException(sprintf("Invalid method name '%s'", $methodName));
                }
            }
            else {
                $methodToCall = $methodName;
            }

            $convertedParameters = $container->resolveMany($parameters);
            call_user_func_array(array($service, $methodToCall), $convertedParameters);
        }

        return true;
    }
}

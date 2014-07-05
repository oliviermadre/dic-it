<?php

namespace DICIT\Injectors;

use DICIT\Injector;
use DICIT\Container;

class PropertyInjector implements Injector
{
    public function inject(Container $container, $service, array $serviceConfig)
    {
        $propConfig = array();

        if (array_key_exists('props', $serviceConfig)) {
            $propConfig = $serviceConfig['props'];
        }

        foreach($propConfig as $propName => $propValue) {
            $convertedValue = $container->resolve($propValue);
            $service->$propName = $convertedValue;
        }

        return true;
    }
}

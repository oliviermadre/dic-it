<?php
namespace DICIT\Activators;

use DICIT\Activator;
use DICIT\Container;

class LazyActivator implements Activator
{

    private $activator;

    public function __construct(Activator $activator)
    {
        $this->activator = $activator;
    }

    public function createInstance(Container $container, $serviceName, array $serviceConfig)
    {
        $factory = new \ProxyManager\Factory\LazyLoadingValueHolderFactory();
        $activator = $this->activator;

        $proxy = $factory->createProxy($serviceConfig['class'],
            function (& $wrappedObject, $proxy, $method, $parameters, & $initializer) use($activator, $container,
            $serviceName, $serviceConfig)
            {
                $wrappedObject = $activator->createInstance($container, $serviceName, $serviceConfig);
                $initializer = null;

                return true;
            });

        return $proxy;
    }
}
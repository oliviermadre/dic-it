<?php
namespace DICIT\Activators;

use DICIT\Container;
use ProxyManager\Configuration;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;

class LazyActivator implements Activator
{
    private $activator;
    private $configuration = null;

    public function __construct(Activator $activator, Configuration $configuration = null)
    {
        $this->activator = $activator;
        $this->configuration = $configuration;
    }

    public function getWrappedInstance()
    {
        return $this->activator;
    }

    public function createInstance(Container $container, $serviceName, array $serviceConfig)
    {
        if (! isset($serviceConfig['lazy']) || ! $serviceConfig['lazy']) {
            return $this->activator->createInstance($container, $serviceName, $serviceConfig);
        }

        $activator = $this->activator;
        $factory = new LazyLoadingValueHolderFactory($this->configuration);

        $proxy = $factory->createProxy(
            $serviceConfig['class'],
            function (& $wrappedObject, $proxy, $method, $parameters, &$initializer) use ($activator, $container, $serviceName, $serviceConfig) {
                $wrappedObject = $activator->createInstance($container, $serviceName, $serviceConfig);
                $initializer = null;
                $container->inject($wrappedObject, $serviceConfig);
                $container->encapsulate($wrappedObject, $serviceConfig);
                return true;
            }
        );

        return $proxy;
    }

    /**
     * @param array $serviceConfig
     * @return mixed
     */
    public function canActivate(array $serviceConfig)
    {
        return $this->activator->canActivate($serviceConfig);
    }
}

<?php
namespace DICIT\Activators;

use DICIT\Activator;
use DICIT\Container;
use DICIT\UnbuildableServiceException;

class RemoteActivator implements Activator
{

    private $adapterFactory;

    public function __construct(RemoteAdapterFactory $adapterFactory)
    {
        $this->adapterFactory = $adapterFactory;
    }

    public function createInstance(Container $container, $serviceName, array $serviceConfig)
    {
        if (! isset($serviceConfig['remote']) || ! isset($serviceConfig['class'])) {
            throw new UnbuildableServiceException(
                sprintf("No remote configuration available for service '%'.", $serviceName));
        }

        $className = $serviceConfig['class'];
        $remoteConfig = $serviceConfig['remote'];

        $adapter = $this->adapterFactory->getAdapter($serviceName, $serviceConfig['remote']);
        $factory = new \ProxyManager\Factory\RemoteObjectFactory($adapter);

        return $factory->createProxy($className);
    }
}

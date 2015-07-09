<?php
namespace DICIT\Activators;

use DICIT\Activator;
use DICIT\Container;
use DICIT\UnbuildableServiceException;
use DICIT\Util\ParamsResolver;
use ProxyManager\Configuration;
use ProxyManager\Factory\RemoteObjectFactory;

class RemoteActivator implements Activator
{
    private $adapterFactory;
    private $configuration;

    public function __construct(RemoteAdapterFactory $adapterFactory, Configuration $configuration = null)
    {
        $this->adapterFactory = $adapterFactory;
        $this->configuration = $configuration;
    }

    public function createInstance(Container $container, $serviceName, array $serviceConfig)
    {
        if (! isset($serviceConfig['remote']) || ! isset($serviceConfig['class'])) {
            throw new UnbuildableServiceException(
                sprintf("No remote configuration available for service '%'.", $serviceName)
            );
        }

        $className = $serviceConfig['class'];
        $remoteConfig = $serviceConfig['remote'];
        $convertedRemoteConfig = ParamsResolver::resolveParams($container, $remoteConfig);

        $adapter = $this->adapterFactory->getAdapter($serviceName, $convertedRemoteConfig);
        $factory = new RemoteObjectFactory($adapter, $this->configuration);

        return $factory->createProxy($className);
    }

    /**
     * @param array $serviceConfig
     * @return mixed
     */
    public function canActivate(array $serviceConfig)
    {
        return array_key_exists('remote', $serviceConfig);
    }
}

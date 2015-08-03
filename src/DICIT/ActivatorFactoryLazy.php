<?php

namespace DICIT;

use DICIT\Activators\Remote\JsonRpcAdapterBuilder;
use DICIT\Activators\Remote\RestAdapterBuilder;
use DICIT\Activators\Remote\SoapAdapterBuilder;
use DICIT\Activators\Remote\XmlRpcAdapterBuilder;
use DICIT\Activators\DefaultActivator;
use DICIT\Activators\StaticInvocationActivator;
use DICIT\Activators\InstanceInvocationActivator;
use DICIT\Activators\LazyActivator;
use DICIT\Activators\RemoteActivator;
use DICIT\Activators\RemoteAdapterFactory;

use ProxyManager\Configuration;

class ActivatorFactoryLazy extends ActivatorFactorySimple
{
    public function __construct(Configuration $configuration = null)
    {
        $this->addActivator('builder-static', new LazyActivator(new StaticInvocationActivator(), $configuration), 1);
        $this->addActivator('builder', new LazyActivator(new InstanceInvocationActivator(), $configuration), 1);
        $this->addRemoteActivator($configuration);
        $this->addActivator('default', new LazyActivator(new DefaultActivator(), $configuration), 100);
    }

    private function addRemoteActivator($configuration)
    {
        $remoteAdapterFactory = new RemoteAdapterFactory();
        $remoteAdapterFactory->addAdapterBuilder('xml-rpc', new XmlRpcAdapterBuilder());
        $remoteAdapterFactory->addAdapterBuilder('json-rpc', new JsonRpcAdapterBuilder());
        $remoteAdapterFactory->addAdapterBuilder('soap', new SoapAdapterBuilder());
        $remoteAdapterFactory->addAdapterBuilder('rest', new RestAdapterBuilder());
        $remoteActivator = new RemoteActivator($remoteAdapterFactory, $configuration);
        $lazyRemoteActivator = new LazyActivator($remoteActivator, $configuration);
        $this->addActivator('remote', $lazyRemoteActivator, 1);
    }
}

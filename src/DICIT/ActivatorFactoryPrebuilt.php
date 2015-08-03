<?php

namespace DICIT;

use Activators\Remote\JsonRpcAdapterBuilder;
use Activators\Remote\RestAdapterBuilder;
use Activators\Remote\SoapAdapterBuilder;
use Activators\Remote\XmlRpcAdapterBuilder;
use DICIT\Activators\DefaultActivator;
use DICIT\Activators\StaticInvocationActivator;
use DICIT\Activators\InstanceInvocationActivator;
use DICIT\Activators\RemoteActivator;
use DICIT\Activators\RemoteAdapterFactory;
use ProxyManager\Configuration;

class ActivatorFactoryPrebuilt extends ActivatorFactorySimple
{
    public function __construct(Configuration $configuration = null)
    {
        $this->addActivator('builder-static', new StaticInvocationActivator(), 1);
        $this->addActivator('builder', new InstanceInvocationActivator(), 1);
        $this->addRemoteActivator($configuration);
        $this->addActivator('default', new DefaultActivator(), 100);
    }

    private function addRemoteActivator($configuration)
    {
        $remoteAdapterFactory = new RemoteAdapterFactory();
        $remoteAdapterFactory->addAdapterBuilder('xml-rpc', new XmlRpcAdapterBuilder());
        $remoteAdapterFactory->addAdapterBuilder('json-rpc', new JsonRpcAdapterBuilder());
        $remoteAdapterFactory->addAdapterBuilder('soap', new SoapAdapterBuilder());
        $remoteAdapterFactory->addAdapterBuilder('rest', new RestAdapterBuilder());
        $remoteActivator = new RemoteActivator($remoteAdapterFactory, $configuration);
        $this->addActivator('remote', $remoteActivator, 1);
    }
}

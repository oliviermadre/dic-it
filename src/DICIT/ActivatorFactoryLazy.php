<?php

namespace DICIT;

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
        $this->addActivator('remote', new LazyActivator(new RemoteActivator(new RemoteAdapterFactory()), $configuration), 1);
        $this->addActivator('default', new LazyActivator(new DefaultActivator(), $configuration), 100);
    }
}

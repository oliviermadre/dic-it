<?php

namespace DICIT;

use DICIT\Activators\DefaultActivator;
use DICIT\Activators\StaticInvocationActivator;
use DICIT\Activators\InstanceInvocationActivator;
use DICIT\Activators\LazyActivator;
use DICIT\Activators\RemoteActivator;
use DICIT\Activators\RemoteAdapterFactory;

class ActivatorFactoryLazy extends ActivatorFactorySimple
{
    public function __construct()
    {
        $this->addActivator('builder-static', new LazyActivator(new StaticInvocationActivator()), 1);
        $this->addActivator('builder', new LazyActivator(new InstanceInvocationActivator()), 1);
        $this->addActivator('remote', new LazyActivator(new RemoteActivator(new RemoteAdapterFactory())), 1);
        $this->addActivator('default', new LazyActivator(new DefaultActivator()), 100);
    }
}

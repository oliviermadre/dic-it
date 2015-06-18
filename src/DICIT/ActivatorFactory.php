<?php

namespace DICIT;

use DICIT\Activators\DefaultActivator;
use DICIT\Activators\StaticInvocationActivator;
use DICIT\Activators\InstanceInvocationActivator;
use DICIT\Activators\LazyActivator;
use DICIT\Activators\RemoteActivator;
use DICIT\Activators\RemoteAdapterFactory;

interface ActivatorFactory
{
    /**
     * @param $key
     * @param Activator $activator
     * @param int|null $priority The minus the higher in priority
     * @return mixed
     */
    public function addActivator($key, Activator $activator, $priority = null);

    /**
     * @param $serviceName
     * @param array $configuration
     * @return mixed
     */
    public function getActivator($serviceName, array $configuration);
}

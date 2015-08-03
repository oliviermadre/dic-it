<?php

namespace DICIT;

use DICIT\Activators\Activator;

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

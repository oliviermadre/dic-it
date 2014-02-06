<?php

namespace DICIT;

interface Activator
{
    /**
     *
     * @param Container $container
     * @param string $serviceName
     * @param array $serviceConfig
     */
    public function createInstance(Container $container, $serviceName, array $serviceConfig);
}

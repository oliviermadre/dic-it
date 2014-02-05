<?php

namespace DICIT;

interface Activator
{

    public function createInstance(Container $container, $serviceName, array $serviceConfig);
}

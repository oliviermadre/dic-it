<?php

namespace DICIT;

interface Activator
{
    function createInstance(Container $container, $serviceName, array $serviceConfig);
}

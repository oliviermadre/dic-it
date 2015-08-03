<?php

namespace DICIT\Injectors;

use DICIT\Container;

interface Injector
{

    /**
     * @return boolean
     */
    public function inject(Container $container, $service, array $serviceConfig);
}

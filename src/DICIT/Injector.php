<?php

namespace DICIT;

interface Injector
{

    /**
     * @return boolean
     */
    public function inject(Container $container, $service, array $serviceConfig);
}

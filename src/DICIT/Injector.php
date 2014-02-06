<?php

namespace DICIT;

interface Injector
{

    public function inject(Container $container, $service, array $serviceConfig);
}

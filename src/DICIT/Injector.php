<?php

namespace DICIT;

interface Injector
{
    
    function inject(Container $container, $service, array $serviceConfig);
    
    
}
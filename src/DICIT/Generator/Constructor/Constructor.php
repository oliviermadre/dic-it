<?php

namespace DICIT\Generator\Constructor;

interface Constructor
{
    public function construct($serviceName, $serviceConfig);
    public function canConstruct($serviceConfig);
}
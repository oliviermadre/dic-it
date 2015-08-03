<?php

namespace DICIT\Encapsulators;

use DICIT\Container;

interface Encapsulator
{

    public function encapsulate(Container $container, $object, array $serviceConfig);
}

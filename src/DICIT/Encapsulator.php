<?php

namespace DICIT;

interface Encapsulator
{

    public function encapsulate(Container $container, $object, array $serviceConfig);
}

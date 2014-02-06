<?php

namespace DICIT;

interface Encapsulator
{

    function encapsulate(Container $container, $object, array $serviceConfig);
}

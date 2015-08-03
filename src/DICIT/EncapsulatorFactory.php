<?php

namespace DICIT;

use DICIT\Encapsulators\Encapsulator;
use DICIT\Encapsulators\InterceptorEncapsulator;

class EncapsulatorFactory
{

    private $encapsulators = array();

    public function __construct()
    {
        $this->encapsulators[] = new InterceptorEncapsulator();
    }

    public function addEncapsulator(Encapsulator $encapsulator)
    {
        $this->encapsulators[] = $encapsulator;
    }

    public function getEncapsulators()
    {
        return $this->encapsulators;
    }
}

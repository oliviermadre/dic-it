<?php

namespace DICIT;

use DICIT\Injectors\MethodInjector;
use DICIT\Injectors\PropertyInjector;
class InjectorFactory
{
    
    private $injectors = array();
    
    public function __construct()
    {
        $this->addInjector(new PropertyInjector());
        $this->addInjector(new MethodInjector());
    }
    
    public function addInjector(Injector $injector)
    {
        $this->injectors[] = $injector;
    }
    
    public function getInjectors()
    {
        return $this->injectors;
    }
    
}
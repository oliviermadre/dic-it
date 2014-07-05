<?php

namespace DICIT;

use DICIT\Config\AbstractConfig;

class ContainerFactory
{
    public static function createFromJson($file, array $options = array())
    {
        return $this->createInstance(new PHP($file), $options);
    }
    
    public static function createFromPhp($file, array $options = array())
    {
        return $this->createInstance(new PHP($file), $options);   
    }
    
    public static function createFromYaml($file, array $options = array())
    {
        return $this->createInstance(new PHP($file), $options);
    }
    
    public static function createFromInlineYaml($file, array $options = array())
    {
        return $this->createInstance(new PHP($file), $options);
    }
    
    private static function createInstance(AbstractConfig $config, array $options)
    {
        $pipeline = new ServiceBuilder(
    	    new ActivatorFactory(),
            new InjectorFactory(),
            new EncapsulatorFactory()
        );
        
        return new Container($config, $pipeline);
    }
}
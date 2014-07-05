<?php

namespace DICIT;

use DICIT\Config\AbstractConfig;
use DICIT\Config\YML;
use DICIT\Config\YMLInline;
use DICIT\Config\Json;
use DICIT\Config\PHP;

class ContainerFactory
{
    public static function createFromJson($file, array $options = array())
    {
        return self::createInstance(new Json($file), $options);
    }
    
    public static function createFromPhp($file, array $options = array())
    {
        return self::createInstance(new PHP($file), $options);   
    }
    
    public static function createFromYaml($file, array $options = array())
    {
        return self::createInstance(new YML($file), $options);
    }
    
    public static function createFromInlineYaml($yaml, array $options = array())
    {
        return self::createInstance(new YMLInline($yaml), $options);
    }
    
    public static function create(AbstractConfig $config, array $options = array())
    {
        return self::createInstance($config, $options);
    }
    
    private static function createInstance(AbstractConfig $config, array $options)    
    {
        $resolver = new ArrayResolver($options);
        
        $deferred = (bool)$resolver->resolve('deferred', false);
        
        $pipeline = new ServiceBuilder(
            new ActivatorFactory($deferred),
            new InjectorFactory(),
            new EncapsulatorFactory()
        );
        
        return new Container($config, $pipeline);
    }
}

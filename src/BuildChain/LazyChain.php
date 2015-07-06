<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:27
 */

namespace Pyrite\DI\BuildChain;

use \ProxyManager\Factory\LazyLoadingValueHolderFactory;

class LazyChain extends AbstractChain
{
    protected function canProcess($serviceConfig)
    {
        if(array_key_exists('lazy', $serviceConfig) && array_key_exists('class', $serviceConfig)) {
            return true;
        }
        return false;
    }

    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        $factory = new LazyLoadingValueHolderFactory();

        $class = $serviceConfig['class'];

        $buildChain = $this->next;

        $proxy = $factory->createProxy($class,
            function (& $wrappedObject, $proxy, $method, $parameters, & $initializer) use ($serviceConfig, $serviceName, $buildChain, $instance)
            {
                $wrappedObject = $buildChain->process($serviceConfig, $serviceName, $instance);
                $initializer = null;

                return true;
            });

        return $proxy;
    }
}

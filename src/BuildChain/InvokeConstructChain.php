<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:33
 */

namespace Pyrite\DI\BuildChain;

use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

class InvokeConstructChain extends AbstractChain
{
    protected $referenceResolver;

    public function __construct(ReferenceResolverDispatcher $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }

    protected function canProcess($serviceConfig)
    {
        if (array_key_exists('class', $serviceConfig)) {
            return true;
        }
        return false;
    }

    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        $className = $serviceConfig['class'];
        $class = new \ReflectionClass($className);

        if(array_key_exists('arguments', $serviceConfig)) {
            $arguments = $serviceConfig['arguments'];
            $args = array();
            foreach($arguments as $arg) {
                $args[] = $this->referenceResolver->resolve($arg);
            }
            return $class->newInstanceArgs($args);
        }

        return $class->newInstance();
    }
}
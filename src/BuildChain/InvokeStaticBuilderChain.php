<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:33
 */

namespace Pyrite\DI\BuildChain;

use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

class InvokeStaticBuilderChain extends AbstractChain
{
    protected $referenceResolver;

    public function __construct(ReferenceResolverDispatcher $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }

    protected function canProcess($serviceConfig)
    {
        if(array_key_exists('builder', $serviceConfig) && false !== strpos($serviceConfig['builder'], '::')) {
            return true;
        }
    }

    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        $builder = $serviceConfig['builder'];
        $callable = explode('::', $builder);

        $args = $serviceConfig;
        if(array_key_exists('arguments', $serviceConfig)) {
            $arguments = $serviceConfig['arguments'];
            $args = array();
            foreach($arguments as $arg) {
                $args[] = $this->referenceResolver->resolve($arg);
            }
        }

        return call_user_func_array($callable, $args);
    }
}
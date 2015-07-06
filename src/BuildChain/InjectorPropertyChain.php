<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:34
 */

namespace Pyrite\DI\BuildChain;

use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

class InjectorPropertyChain extends AbstractChain
{
    protected $resolver;

    public function __construct(ReferenceResolverDispatcher $resolver)
    {
        $this->resolver = $resolver;
    }

    protected function canProcess($serviceConfig)
    {
        return array_key_exists('props', $serviceConfig);
    }

    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        if(is_object($instance)) {
            $props = $serviceConfig['props'];
            foreach($props as $key => $value) {
                $instance->$key = $this->resolver->resolve($value);
            }
        }

        if($this->next) {
            return $this->next->process($serviceConfig, $serviceName, $instance);
        }

        return $instance;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:35
 */

namespace Pyrite\DI\BuildChain;

use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

class InjectorMethodChain extends AbstractChain
{
    protected $resolver;

    public function __construct(ReferenceResolverDispatcher $resolver)
    {
        $this->resolver = $resolver;
    }

    protected function canProcess($serviceConfig)
    {
        return array_key_exists('call', $serviceConfig);
    }

    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        if(is_object($instance)) {
            $calls = $serviceConfig['call'];
            foreach($calls as $methodName => $arguments) {
                $resolvedArguments = array();
                foreach($arguments as $arg) {
                    $resolved = $this->resolver->resolve($arg);
                    $resolvedArguments[] = $resolved;
                }

                call_user_func_array(array($instance, $methodName), $resolvedArguments);
            }
        }

        if($this->next) {
            return $this->next->process($serviceConfig, $serviceName, $instance);
        }

        return $instance;
    }
}
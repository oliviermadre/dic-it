<?php
namespace DICIT\Encapsulators;

use DICIT\Encapsulator;
use DICIT\Container;

class InterceptorEncapsulator implements Encapsulator
{

    public function encapsulate(Container $container, $object, array $serviceConfig)
    {
        if (array_key_exists('interceptor', $serviceConfig)) {
            foreach ($serviceConfig['interceptor'] as $interceptorName) {
                $interceptor = $container->resolve($interceptorName);

                if (! is_object($interceptor)) {
                    throw new \RuntimeException(
                        'The interceptor ' . $interceptorName . ' does not reference a known service'
                    );
                }

                $interceptor->setDecorated($object);
                $object = $interceptor;
            }
        }

        return $object;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 14:19
 */

namespace Pyrite\DI\ReferenceResolver;


class ReferenceResolverDispatcher
{
    protected $resolvers = array();

    public function addResolver(ReferenceResolver $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    public function resolve($key)
    {
        foreach($this->resolvers as $resolver) {
            if($resolver->canResolve($key)) {
                return $resolver->resolve($key);
            }
        }
        return $key;
    }
}
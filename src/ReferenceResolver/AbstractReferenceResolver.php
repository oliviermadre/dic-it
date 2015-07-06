<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 16:04
 */

namespace Pyrite\DI\ReferenceResolver;


abstract class AbstractReferenceResolver implements ReferenceResolver
{
    public function resolve($key)
    {
        if($this->canResolve($key)) {
            return $this->runResolve($key);
        }
        return null;
    }

    abstract public function canResolve($key);
    abstract protected function runResolve($key);
}
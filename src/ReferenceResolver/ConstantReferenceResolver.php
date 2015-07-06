<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 19:16
 */

namespace Pyrite\DI\ReferenceResolver;


use Pyrite\DI\Container;

class ConstantReferenceResolver extends AbstractReferenceResolver
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function canResolve($key)
    {
        return strpos($key, '$const.') === 0;
    }

    protected function runResolve($key)
    {
        $constName = substr($key, 7);
        if(defined($constName)) {
            return constant($constName);
        }

        throw new \RuntimeException(sprintf("Couldn't resolve '%s', constant undefined", $key));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 19:16
 */

namespace Pyrite\DI\ReferenceResolver;


use Pyrite\DI\Container;

class ParameterReferenceResolver extends AbstractReferenceResolver
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function canResolve($key)
    {
        return $key{0} === '%';
    }

    protected function runResolve($key)
    {
        try {
            return $this->container->getParameter(substr($key, 1));
        }
        catch(\Exception $e) {
            throw new \RuntimeException(sprintf("Couldn't resolve '%s'", $key));
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 14:16
 */

namespace Pyrite\DI\ReferenceResolver;


use Pyrite\DI\Container;

class ContainerReferenceResolver extends AbstractReferenceResolver
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function canResolve($key)
    {
        return $key === '$container';
    }

    protected function runResolve($key)
    {
        return $this->container;
    }

}
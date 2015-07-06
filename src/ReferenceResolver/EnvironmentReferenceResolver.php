<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 19:12
 */

namespace Pyrite\DI\ReferenceResolver;


class EnvironmentReferenceResolver extends AbstractReferenceResolver
{
    public function canResolve($key)
    {
        return strpos($key, '$env.') === 0;
    }

    protected function runResolve($key)
    {
        $varName = substr($key, 5);
        return getenv($varName);
    }

}
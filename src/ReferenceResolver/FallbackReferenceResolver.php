<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 16:14
 */

namespace Pyrite\DI\ReferenceResolver;


class FallbackReferenceResolver extends AbstractReferenceResolver {
    public function canResolve($key)
    {
        return true;
    }

    protected function runResolve($key)
    {
        return $key;
    }

}
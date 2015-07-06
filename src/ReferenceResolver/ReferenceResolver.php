<?php

namespace Pyrite\DI\ReferenceResolver;


interface ReferenceResolver
{
    public function canResolve($key);
    public function resolve($key);
}
<?php

namespace FooSpace;

interface FooInterface
{
    public function foo($param);
}

class Foo implements FooInterface
{
    public function foo($param)
    {
        var_dump($param);
    }
}

$class = 'FooSpace\Foo';
$object = new $class;

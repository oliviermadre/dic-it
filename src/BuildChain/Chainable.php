<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:26
 */

namespace Pyrite\DI\BuildChain;

interface Chainable
{
    public function setNext(Chainable $chain);
    public function process($serviceConfig, $serviceName, $instance);
}
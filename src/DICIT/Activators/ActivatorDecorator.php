<?php
namespace DICIT\Activators;

interface ActivatorDecorator extends Activator
{
    public function setNext(Activator $activator);
}

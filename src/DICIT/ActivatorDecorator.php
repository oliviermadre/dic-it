<?php
namespace DICIT;

interface ActivatorDecorator extends Activator
{
    public function setNext(Activator $activator);
}

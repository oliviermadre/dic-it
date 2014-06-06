<?php
namespace DICIT;

interface ActivatorDecorator extends Activator
{
    function setNext(Activator $activator);
}

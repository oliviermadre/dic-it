<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 18:01
 */

namespace Pyrite\DI\BuildSequence;


interface BuildSequence {
    public function process($serviceConfig, $serviceName);
}
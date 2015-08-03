<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 19/06/15
 * Time: 19:19
 */

namespace DICIT\Generator\Modifier;

interface Modifier
{
    public function canModify(array $serviceConfig = array());
    public function modify($serviceName, array $serviceConfig = array());
}
<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 19/06/15
 * Time: 19:22
 */

namespace DICIT\Generator;

use DICIT\Generator\Constructor\Constructor;

class ConstructorFactory
{
    protected $constructors = array();

    public function addConstructor(Constructor $constructor, $priority = null)
    {
        $this->constructors[] = $constructor;
    }

    /**
     * @param $serviceConfig
     * @return bool|Constructor
     */
    public function get($serviceConfig)
    {
        /** @var Constructor $constructor */
        foreach ($this->constructors as $constructor) {
            if ($constructor->canConstruct($serviceConfig)) {
                return $constructor;
            }
        }

        return false;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 19/06/15
 * Time: 19:17
 */

namespace DICIT\Generator;


use DICIT\Generator\Modifier\Modifier;

class ModifierFactory
{
    protected $modifiers = array();

    public function addModifier(Modifier $modifier, $priority = null)
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * @param $serviceConfig
     * @return bool|Constructor
     */
    public function get($serviceConfig)
    {
        $out = array();

        /** @var Modifier $modifier */
        foreach ($this->modifiers as $modifier) {
            if ($modifier->canModify($serviceConfig)) {
                $out[] = $modifier;
            }
        }

        return $out;
    }
}
<?php

namespace DICIT\Generator;

use DICIT\Generator\Modifier\MethodCallModifier;
use DICIT\Generator\Modifier\SecurityModifier;

class ModifierFactoryProvider
{
    public function createWithDefault(ArgumentTransformerFactory $argumentTransformerFactory)
    {
        $modifierFactory = new ModifierFactory();
        $this->populateWithDefault($modifierFactory, $argumentTransformerFactory);
        return $modifierFactory;
    }

    public function populateWithDefault(ModifierFactory $modifierFactory, ArgumentTransformerFactory $argumentTransformerFactory)
    {
        $modifierFactory->addModifier(new MethodCallModifier($argumentTransformerFactory));
        $modifierFactory->addModifier(new SecurityModifier());
    }
}
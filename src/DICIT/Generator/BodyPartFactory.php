<?php

namespace DICIT\Generator;

use DICIT\Generator\BodyPart\ConstructorChain;
use DICIT\Generator\BodyPart\InitializationChain;
use DICIT\Generator\BodyPart\LazyWrapperChain;
use DICIT\Generator\BodyPart\ModifierChain;
use DICIT\Generator\BodyPart\ReturnChain;
use DICIT\Generator\BodyPart\SingletonGetChain;
use DICIT\Generator\BodyPart\SingletonSetChain;

class BodyPartFactory
{
    public function get(ConstructorFactory $constructorFactory, ModifierFactory $modifierFactory)
    {
        $mainChain = new InitializationChain();
        $singletonGetChain = new SingletonGetChain();
        $lazyChain = new LazyWrapperChain();
        $singletonSetPostProcessChain = new SingletonSetChain();
        $singletonSetPostConstructChain = new SingletonSetChain();
        $constructChain = new ConstructorChain($constructorFactory);
        $modifierChain = new ModifierChain($modifierFactory);
        $returnChain = new ReturnChain();

        $mainChain  ->setNext($singletonGetChain)
            ->setNext($lazyChain)
            ->setNext($singletonSetPostProcessChain)
            ->setNext($returnChain);

        $constructChain ->setNext($singletonSetPostConstructChain)
            ->setNext($modifierChain);

        $lazyChain->setWrappedChain($constructChain);

        return $mainChain;
    }
}

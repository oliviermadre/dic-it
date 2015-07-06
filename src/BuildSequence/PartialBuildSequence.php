<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 17:42
 */

namespace Pyrite\DI\BuildSequence;

use Pyrite\DI\BuildChain\ExtendChain;
use Pyrite\DI\BuildChain\ForkAndForwardChain;
use Pyrite\DI\BuildChain\InjectorMethodChain;
use Pyrite\DI\BuildChain\InjectorPropertyChain;
use Pyrite\DI\BuildChain\InjectorTagChain;
use Pyrite\DI\BuildChain\InvokeConstructChain;
use Pyrite\DI\BuildChain\InvokeStaticBuilderChain;
use Pyrite\DI\BuildChain\LazyChain;
use Pyrite\DI\BuildChain\SingletonChain;

use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

class PartialBuildSequence extends AbstractBuildSequence {
    protected function buildSequence(ReferenceResolverDispatcher $referenceResolverDispatcher)
    {
        $methodInjector = new InjectorMethodChain($referenceResolverDispatcher);
        $tagInjector = new InjectorTagChain(($this->container));


        $defaultConstructor = new InvokeConstructChain($referenceResolverDispatcher);

        $constructChain = new ForkAndForwardChain();
        $constructChain->add($defaultConstructor);

        $constructChainSingleton = clone $constructChain;

        $singletonChain = new SingletonChain();

        $singletonChain
            ->setNext($constructChain)
            ->setNext($methodInjector)
            ->setNext($tagInjector)
        ;

        $singletonChain
            ->setSingletonChain($constructChainSingleton);

        $singletonChain
            ->setSingletonPostChain($methodInjector);

        return $singletonChain;
    }
}
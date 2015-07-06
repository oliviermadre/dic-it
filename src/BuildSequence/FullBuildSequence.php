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

class FullBuildSequence extends AbstractBuildSequence {
    protected function buildSequence(ReferenceResolverDispatcher $referenceResolverDispatcher)
    {
        $methodInjector = new InjectorMethodChain($referenceResolverDispatcher);
        $propertyInjector = new InjectorPropertyChain($referenceResolverDispatcher);
        $tagInjector = new InjectorTagChain(($this->container));

        $staticBuilderConstructor = new InvokeStaticBuilderChain($referenceResolverDispatcher);
        $defaultConstructor = new InvokeConstructChain($referenceResolverDispatcher);

        $constructChain = new ForkAndForwardChain();
        $constructChain->add($staticBuilderConstructor);
        $constructChain->add($defaultConstructor);

        $constructChainSingleton = clone $constructChain;

        $singletonChain = new SingletonChain();

        $extendChain = new ExtendChain($this->container);
        $extendChain
            ->setNext($singletonChain);

        $singletonChain
            ->setNext(new LazyChain())
            ->setNext($constructChain)
            ->setNext($propertyInjector)
            ->setNext($methodInjector)
            ->setNext($tagInjector)
        ;

        $singletonChain
            ->setSingletonChain(new LazyChain())
            ->setNext($constructChainSingleton);

        $singletonChain
            ->setSingletonPostChain($propertyInjector);

        return $extendChain;
    }
}
<?php

namespace DICIT\Generator;

use DICIT\Generator\Constructor\DefaultConstructor;
use DICIT\Generator\Constructor\InstanceBuilderConstructor;
use DICIT\Generator\Constructor\RemoteConstructor;
use DICIT\Generator\Constructor\StaticBuilderConstructor;

class ConstructorFactoryProvider
{
    public function createWithDefault(ArgumentTransformerFactory $argumentTransformerFactory)
    {
        $constructorFactory = new ConstructorFactory();
        $this->populateWithDefault($constructorFactory, $argumentTransformerFactory);
        return $constructorFactory;
    }

    public function populateWithDefault(ConstructorFactory $constructorFactory, ArgumentTransformerFactory $argumentTransformerFactory)
    {
        $constructorFactory->addConstructor(new RemoteConstructor($argumentTransformerFactory, 'RemoteAdapterFactory'));
        $constructorFactory->addConstructor(new StaticBuilderConstructor($argumentTransformerFactory));
        $constructorFactory->addConstructor(new InstanceBuilderConstructor($argumentTransformerFactory));
        $constructorFactory->addConstructor(new DefaultConstructor($argumentTransformerFactory));
    }
}
<?php

namespace DICIT\Generator;

use DICIT\Config\AbstractConfig;

use DICIT\Generator\ArgumentTransformer\ArrayArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ConstantArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ContainerArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\EmptyStringArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\EnvironmentArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\NullArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ParameterArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ScalarArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ServiceArgumentTransformer;
use DICIT\Generator\ArgumentTransformer\ServiceLazyArgumentTransformer;

class ArgumentTransformerFactoryProvider
{
    public function createWithDefault(AbstractConfig $config)
    {
        $argumentTransformerFactory = new ArgumentTransformerFactory();
        $this->populateWithDefault($argumentTransformerFactory, $config);
        return $argumentTransformerFactory;
    }

    public function populateWithDefault(ArgumentTransformerFactory $argumentTransformerFactory, AbstractConfig $config)
    {
        $argumentTransformerFactory->addArgumentTransformer(new NullArgumentTransformer());
        $argumentTransformerFactory->addArgumentTransformer(new EmptyStringArgumentTransformer());
        $argumentTransformerFactory->addArgumentTransformer(new ServiceArgumentTransformer());
        $argumentTransformerFactory->addArgumentTransformer(new ServiceLazyArgumentTransformer($config));
        $argumentTransformerFactory->addArgumentTransformer(new ParameterArgumentTransformer());
        $argumentTransformerFactory->addArgumentTransformer(new ContainerArgumentTransformer());
        $argumentTransformerFactory->addArgumentTransformer(new EnvironmentArgumentTransformer());
        $argumentTransformerFactory->addArgumentTransformer(new ConstantArgumentTransformer());
        $argumentTransformerFactory->addArgumentTransformer(new ArrayArgumentTransformer($argumentTransformerFactory));
        $argumentTransformerFactory->addArgumentTransformer(new ScalarArgumentTransformer());
    }
}
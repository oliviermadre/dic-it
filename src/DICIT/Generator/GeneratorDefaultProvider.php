<?php

namespace DICIT\Generator;

use DICIT\Config\AbstractConfig;
use DICIT\Generator\BodyPart\BodyPart;

class GeneratorDefaultProvider
{
    /**
     * @var ArgumentTransformerFactory
     */
    protected $argumentTransformerFactory;
    /**
     * @var ConstructorFactory
     */
    protected $constructorFactory;
    /**
     * @var ModifierFactory
     */
    protected $modifierFactory;
    /**
     * @var BodyPart
     */
    protected $buildChain;

    public function __construct()
    {
        $this->argumentTransformerFactory = new ArgumentTransformerFactory();
        $this->constructorFactory = new ConstructorFactory();
        $this->modifierFactory = new ModifierFactory();

        $bodyPartFactory = new BodyPartFactory();
        $this->buildChain = $bodyPartFactory->get($this->constructorFactory, $this->modifierFactory);
    }

    public function populateWithDefault(AbstractConfig $config)
    {
        $a = new ArgumentTransformerFactoryProvider();
        $a->populateWithDefault($this->argumentTransformerFactory, $config);

        $c = new ConstructorFactoryProvider();
        $c->populateWithDefault($this->constructorFactory, $this->argumentTransformerFactory);

        $m = new ModifierFactoryProvider();
        $m->populateWithDefault($this->modifierFactory, $this->argumentTransformerFactory);

        return $this;
    }

    /**
     * @return ArgumentTransformerFactory
     */
    public function getArgumentTransformerFactory()
    {
        return $this->argumentTransformerFactory;
    }

    /**
     * @return ConstructorFactory
     */
    public function getConstructorFactory()
    {
        return $this->constructorFactory;
    }

    /**
     * @return ModifierFactory
     */
    public function getModifierFactory()
    {
        return $this->modifierFactory;
    }

    /**
     * @return BodyPart
     */
    public function getBuildChain()
    {
        return $this->buildChain;
    }
}

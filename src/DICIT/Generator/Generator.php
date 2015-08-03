<?php

namespace DICIT\Generator;

use DICIT\Config\AbstractConfig;

use DICIT\Generator\BodyPart\BodyPart;
use ReflectionClass;
use RuntimeException;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

class Generator implements ContainerGenerator
{
    protected $nameGeneratorUtil;
    protected $constructorFactory;
    protected $modifierFactory;
    protected $buildChain;
    protected $argumentTransformerFactory;

    public function __construct(
        ArgumentTransformerFactory $argumentTransformerFactory,
        ConstructorFactory $constructorFactory,
        ModifierFactory $modifierFactory,
        BodyPart $buildChain
    ) {
        $this->argumentTransformerFactory = $argumentTransformerFactory;
        $this->constructorFactory = $constructorFactory;
        $this->modifierFactory = $modifierFactory;
        $this->buildChain = $buildChain;

        $this->nameGeneratorUtil = new NameGeneratorUtil();
    }

    /**
     * Convenient method to create a Generator using a factory wrapper object
     * @param GeneratorDefaultProvider $provider
     * @return Generator
     */
    public static function createFromProvider(GeneratorDefaultProvider $provider)
    {
        $atf = $provider->getArgumentTransformerFactory();
        $cf = $provider->getConstructorFactory();
        $mf = $provider->getModifierFactory();
        $bc = $provider->getBuildChain();

        return new self($atf, $cf, $mf, $bc);
    }

    public function generate(AbstractConfig $config, $generatedNamespace = null, $generatedClassname = null)
    {
        $data = $config->load();

        $classGenerator = new ClassGenerator();
        $classGenerator->setName($generatedClassname ?: 'GeneratedServiceLocator');
        $classGenerator->setExtendedClass('\DICIT\Generator\BaseServiceLocator');
        $classGenerator->setNamespaceName($generatedNamespace ?: 'DICIT');
        $classGenerator->addUse('\DICIT\Generator\BaseServiceLocator');

        $this->buildParameters($classGenerator, $data);
        $this->buildServices($classGenerator, $data);

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($classGenerator);

        return $fileGenerator->generate();
    }

    protected function buildParameters(ClassGenerator $classGenerator, $data)
    {
        $property = new PropertyGenerator('parameters');
        $property->setVisibility(PropertyGenerator::VISIBILITY_PROTECTED);

        $parameters = array();
        if (array_key_exists('parameters', $data)) {
            $parameters = $data['parameters'];
        }

        $values = $parameters;

        $property->setDefaultValue($values);
        $classGenerator->addPropertyFromGenerator($property);
    }

    protected function buildServices(ClassGenerator $classGenerator, $data)
    {
        $services = array();
        if (array_key_exists('classes', $data)) {
            $services = $data['classes'];
        }

        $reflectionExtendedClass = new ReflectionClass($classGenerator->getExtendedClass());

        foreach ($services as $serviceName => $serviceConfig) {
            $this->buildService($classGenerator, $reflectionExtendedClass, $serviceName, $serviceConfig);
        }
    }

    protected function buildService(
        ClassGenerator $classGenerator,
        ReflectionClass $reflectionExtendedClass,
        $serviceName,
        $serviceConfig
    ) {
        $methodName = $this->nameGeneratorUtil->getter($serviceName);
        $this->assertMethodNameIsAvailable($reflectionExtendedClass, $methodName);

        $bodyCode = $this->buildChain->handle($serviceName, $serviceConfig);

        $methodGenerator = new MethodGenerator();
        $methodGenerator->setName($methodName);
        $methodGenerator->setVisibility(MethodGenerator::VISIBILITY_PROTECTED);
        $methodGenerator->setBody($bodyCode);
        $classGenerator->addMethodFromGenerator($methodGenerator);
    }

    protected function assertMethodNameIsAvailable(ReflectionClass $reflectionExtendedClass, $methodName)
    {
        if ($reflectionExtendedClass->hasMethod($methodName)) {
            throw new RuntimeException(sprintf("Can't generate method \"%s\", overlap detected", $methodName));
        }

        return true;
    }
}

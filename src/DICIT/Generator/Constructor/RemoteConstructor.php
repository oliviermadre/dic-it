<?php

namespace DICIT\Generator\Constructor;

use DICIT\Generator\ArgumentTransformerFactory;

class RemoteConstructor implements Constructor
{
    private $argumentTransformerFactory;
    private $remoteAdapterFactoryName;

    public function __construct(ArgumentTransformerFactory $factory, $remoteAdapterFactoryName)
    {
        $this->argumentTransformerFactory = $factory;
        $this->remoteAdapterFactoryName = $remoteAdapterFactoryName;
    }

    public function construct($serviceName, $serviceConfig)
    {
        $className = $serviceConfig['class'];
        $arguments = array_key_exists('remote', $serviceConfig) ? $serviceConfig['remote'] : array();
        $argumentsAsString = $this->argumentTransformerFactory->transformMany(array($serviceName, $arguments));

        return <<<PHP
\$adapter = \$container->get('$this->remoteAdapterFactoryName')->getAdapter($argumentsAsString);
\$factory = new \ProxyManager\Factory\RemoteObjectFactory(\$adapter, \$container->getLazyConfig());
\$instance = \$factory->createProxy('$className');

PHP;


    }

    public function canConstruct($serviceConfig)
    {
        return array_key_exists('remote', $serviceConfig);
    }
}

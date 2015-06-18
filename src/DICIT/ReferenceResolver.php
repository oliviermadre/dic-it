<?php

namespace DICIT;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;

class ReferenceResolver
{
    const CONTAINER_REGEXP    = '`^\$container$`i';
    const ENVIRONMENT_REGEXP  = '`^\$env\.(.*)$`i';
    const CONSTANT_REGEXP     = '`^\$const\.(.*)$`i';
    /**
     *
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Return the resolved value of the given reference
     * @param  mixed $reference
     * @return mixed
     */
    public function resolve($reference)
    {
        if (!is_string($reference)) {
            return $reference;
        }
        $prefix = substr($reference, 0, 1);

        switch (1) {
            case $prefix    === '~'                                             : return $this->proxify(substr($reference, 1));
            case $prefix    === '@'                                             : return $this->container->get(substr($reference, 1));
            case $prefix    === '%'                                             : return $this->container->getParameter(substr($reference, 1));
            case preg_match(static::CONTAINER_REGEXP, $reference, $matches)     : return $this->container;
            case preg_match(static::ENVIRONMENT_REGEXP, $reference, $matches)   : return getenv($matches[1]);
            case preg_match(static::CONSTANT_REGEXP, $reference, $matches)      : return constant($matches[1]);
            default                                                             : return $reference;
        }
    }

    /**
     * @param $serviceName
     * @throws UnbuildableServiceException
     */
    public function proxify($serviceName)
    {
        $lazyConfig = null;
        if ($this->container->has('OcramiusCacheConfiguration')) {
            $lazyConfig = $this->container->get('OcramiusCacheConfiguration');
        }
        $factory = new LazyLoadingValueHolderFactory($lazyConfig);
        $container = $this->container;

        $serviceConfigObject = $container->getServiceConfig($serviceName);
        $serviceConfig = array();
        if ($serviceConfigObject) {
            $serviceConfig = $serviceConfigObject->extract();
        }

        if (!array_key_exists('class', $serviceConfig)) {
            throw new UnbuildableServiceException(
                sprintf("Can't make a proxified instance for service '%s'", $serviceName)
            );
        }

        $proxy = $factory->createProxy(
            $serviceConfig['class'],
            function (& $wrappedObject, $proxy, $method, $parameters, &$initializer) use ($container, $serviceName) {
                $wrappedObject = $container->get($serviceName);
                $initializer = null;
                return true;
            }
        );

        return $proxy;
    }

    public function resolveMany(array $references)
    {
        $convertedParameters = array();

        foreach ($references as $reference) {
            $convertedValue = $this->resolve($reference);
            $convertedParameters[] = $convertedValue;
        }

        return $convertedParameters;
    }
}

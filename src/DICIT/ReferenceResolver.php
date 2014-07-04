<?php

namespace DICIT;

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
        $prefix = substr($reference, 0, 1);

        switch (1) {
            case !is_string($reference)                                         : return $reference;
            case $prefix    === '@'                                             : return $this->container->get(substr($reference, 1));
            case $prefix    === '%'                                             : return $this->container->getParameter(substr($reference, 1));
            case preg_match(static::CONTAINER_REGEXP, $reference, $matches)     : return $this->container;
            case preg_match(static::ENVIRONMENT_REGEXP, $reference, $matches)   : return getenv($matches[1]);
            case preg_match(static::CONSTANT_REGEXP, $reference, $matches)      : return constant($matches[1]);
            default                                                             : return $reference;
        }
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

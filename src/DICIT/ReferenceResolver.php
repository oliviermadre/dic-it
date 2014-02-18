<?php

namespace DICIT;

class ReferenceResolver
{
    const CONTAINER_REFERENCE = '$container';
    /**
     *
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function resolve($reference)
    {
        if ($reference === static::CONTAINER_REFERENCE) {
            return $this->container;
        }

        $prefix = substr($reference, 0, 1);

        switch ($prefix) {

            case '@' :
                $toReturn = $this->container->get(substr($reference, 1));
                break;
            case '%' :
                $toReturn = $this->container->getParameter(substr($reference, 1));
                break;
            default :
                $toReturn = $reference;
                break;
        }

        return $toReturn;
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

<?php

namespace DICIT\Generator\BodyPart;

use DICIT\Generator\ConstructorFactory;

class ConstructorChain implements BodyPart
{
    /**
     * @var BodyPart
     */
    protected $next;

    /**
     * @var ConstructorFactory|null
     */
    protected $constructorFactory = null;

    public function __construct(ConstructorFactory $factory)
    {
        $this->constructorFactory = $factory;
    }

    public function handle($serviceName, $serviceConfig)
    {
        $constructor = $this->constructorFactory->get($serviceConfig);

        $code = $constructor->construct($serviceName, $serviceConfig);

        if ($this->next) {
            $code .= $this->next->handle($serviceName, $serviceConfig);
        }

        return $code;
    }

    public function setNext(BodyPart $part)
    {
        $this->next = $part;
        return $this->next;
    }
}
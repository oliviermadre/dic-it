<?php
namespace DICIT\Activators;

use DICIT\ActivatorDecorator;
use DICIT\Container;
use DICIT\UnbuildableServiceException;
use DICIT\Activator;

class AbstractActivatorDecorator implements ActivatorDecorator
{
    /**
     *
     * @var Activator
     */
    protected $wrappedActivator = null;

    public function setNext(Activator $activator)
    {
        $this->wrappedActivator = $activator;
        return $this;
    }

    public function createInstance(Container $container, $serviceName, array $serviceConfig)
    {
        $this->before($container, $serviceName, $serviceConfig);
        $result = $this->aroundNext($container, $serviceName, $serviceConfig);
        return $this->after($container, $serviceName, $serviceConfig, $result);
    }

    protected function before(Container $container, &$serviceName, array &$serviceConfig)
    {
    }

    protected function after(Container $container, &$serviceName, array &$serviceConfig, $returnObject)
    {
        return $returnObject;
    }

    protected function aroundNext(Container $container, &$serviceName, array &$serviceConfig)
    {
        if ($this->wrappedActivator) {
            return $this->wrappedActivator->createInstance($container, $serviceName, $serviceConfig);
        }

        return null;
    }

    /**
     * @param array $serviceConfig
     * @return mixed
     */
    public function canActivate(array $serviceConfig)
    {
        if($this->wrappedActivator) {
            return $this->wrappedActivator->canActivate($serviceConfig);
        }

        return false;
    }
}

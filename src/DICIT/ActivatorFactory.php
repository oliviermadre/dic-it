<?php

namespace DICIT;

use DICIT\Activators\DefaultActivator;
use DICIT\Activators\StaticInvocationActivator;
use DICIT\Activators\InstanceInvocationActivator;
use DICIT\Activators\LazyActivator;
use DICIT\Activators\RemoteActivator;
use DICIT\Activators\RemoteAdapterFactory;

class ActivatorFactory
{

    private $activators = array();
    private $activatorsDecorators = array();

    public function __construct($deferActivations = false)
    {
        $this->addActivator('default', new DefaultActivator(), $deferActivations);
        $this->addActivator('builder-static', new StaticInvocationActivator(), $deferActivations);
        $this->addActivator('builder', new InstanceInvocationActivator(), $deferActivations);
        $this->addActivator('remote', new RemoteActivator(new RemoteAdapterFactory()), $deferActivations);
    }

    /**
     * @param string $key
     * @param boolean $deferredActivations
     */
    public function addActivator($key, Activator $activator, $deferredActivations)
    {
        if ($activator instanceof ActivatorDecorator) {
            $this->activatorsDecorators[$key] = $activator;
        } else {
            if ($deferredActivations) {
                $activator = new LazyActivator($activator);
            }
    
            $this->activators[$key] = $activator;
        }
    }

    /**
     *
     * @param string $serviceName
     * @param array $configuration
     * @throws UnbuildableServiceException
     * @return \DICIT\Activator
     */
    public function getActivator($serviceName, array $configuration)
    {
        $activator = null;
        
        if (array_key_exists('builder', $configuration)) {
            $builderType = $this->getBuilderType($configuration['builder']);

            if ('static' == $builderType) {
                $activator = $this->activators['builder-static'];
            }
            elseif ('instance' == $builderType) {
                $activator = $this->activators['builder'];
            }
        }
        elseif (array_key_exists('class', $configuration)) {
            if (array_key_exists('remote', $configuration)) {
                $activator =  $this->activators['remote'];
            } else {
                $activator =  $this->activators['default'];
            }
        }

        if ($activator == null) {
            throw new UnbuildableServiceException(
                sprintf("Unbuildable service : '%s', no suitable activator found.",$serviceName)
            );
        }
        
        //Decorating the activators if adequate parameters are present
        foreach($this->activatorsDecorators as $key=>$decorator) {
            /* @var $decorator ActivatorDecorator */
            if (array_key_exists($key, $configuration)) {
                $dec = clone $decorator;
                $dec->setNext($activator);
                $activator = $dec;
            }
        }
        
        return $activator;
    }

    private function getBuilderType($builderKey)
    {
        if (false !== strpos($builderKey, '::')) {
            return 'static';
        }
        elseif (false !== strpos($builderKey, '->')) {
            return 'instance';
        }

        return 'null';
    }
}

<?php

namespace DICIT;

use DICIT\Activators\DefaultActivator;
use DICIT\Activators\StaticInvocationActivator;
use DICIT\Activators\InstanceInvocationActivator;
use DICIT\Activators\LazyActivator;

class ActivatorFactory
{

    private $activators = array();

    public function __construct($deferActivations = false) {
        $this->addActivator('default', new DefaultActivator(), $deferActivations);
        $this->addActivator('builder-static', new StaticInvocationActivator(), $deferActivations);
        $this->addActivator('builder', new InstanceInvocationActivator(), $deferActivations);
    }

    private function addActivator($key, Activator $activator, $deferredActivations)
    {
        if ($deferredActivations) {
            $activator = new LazyActivator($activator);
        }

        $this->activators[$key] = $activator;
    }

    /**
     *
     * @param unknown $serviceName
     * @param unknown $configuration
     * @throws UnbuildableServiceException
     * @return \DICIT\Activator
     */
    public function getActivator($serviceName, $configuration)
    {
        if (array_key_exists('builder', $configuration)) {
            $builderType = $this->getBuilderType($configuration['builder']);

            if ('static' == $builderType) {
                return $this->activators['builder-static'];
            }
            elseif ('instance' == $builderType) {
                return $this->activators['builder'];
            }

        } elseif (array_key_exists('class', $configuration)) {
            return $this->activators['default'];
        }

        throw new UnbuildableServiceException(sprintf("Unbuildable service : '%s', no suitable activator found.",
            $serviceName));
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

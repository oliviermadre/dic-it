<?php

namespace DICIT;

use DICIT\Activators\DefaultActivator;
use DICIT\Activators\StaticInvocationActivator;
use DICIT\Activators\InstanceInvocationActivator;
use DICIT\Activators\LazyActivator;

class ActivatorFactory
{

    private $activators = array();

    public function __construct() {
        $this->activators['default'] = new LazyActivator(new DefaultActivator());
        $this->activators['builder-static'] = new LazyActivator(new StaticInvocationActivator());
        $this->activators['builder'] = new LazyActivator(new InstanceInvocationActivator());
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

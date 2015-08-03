<?php

namespace DICIT;

use DICIT\Activators\Activator;
use DICIT\Activators\ActivatorDecorator;
use DICIT\Exception\UnbuildableServiceException;

class ActivatorFactorySimple implements ActivatorFactory
{
    /**
     * @var Activator[]
     */
    private $activators = array();
    /**
     * @var ActivatorDecorator[]
     */
    private $activatorDecorators = array();

    /**
     * @var array
     */
    private $activatorPriority = array();

    /**
     * @param string $key
     * @param Activator $activator
     * @param $priority
     * @return $this
     */
    public function addActivator($key, Activator $activator, $priority = null)
    {
        $priority = $this->sanitizePriority($priority);

        $this->activators[$key] = $activator;
        $this->activatorPriority[$key] = $priority;

        asort($this->activatorPriority);

        return $this;
    }

    /**
     * @param $key
     * @param ActivatorDecorator $decorator
     * @return $this
     */
    public function addActivatorDecorator($key, ActivatorDecorator $decorator)
    {
        $this->activatorDecorators[$key] = $decorator;
        return $this;
    }

    /**
     *
     * @param string $serviceName
     * @param array $configuration
     * @throws UnbuildableServiceException
     * @return Activator
     */
    public function getActivator($serviceName, array $configuration)
    {
        $activatorToUse = null;

        foreach ($this->activatorPriority as $key => $priority) {
            $activator = $this->activators[$key];
            if ($activator->canActivate($configuration)) {
                $activatorToUse = $activator;
                break;
            }
        }

        if (!$activatorToUse) {
            throw new UnbuildableServiceException(
                sprintf("Unbuildable service : '%s', no suitable activator found.", $serviceName)
            );
        }

        // Decorating the activators if adequate parameters are present
        foreach ($this->activatorDecorators as $key => $decorator) {
            /* @var $decorator ActivatorDecorator */
            if (array_key_exists($key, $configuration)) {
                $dec = clone $decorator;
                $dec->setNext($activatorToUse);
                $activatorToUse = $dec;
            }
        }

        return $activatorToUse;
    }

    /**
     * @param null $priority
     * @return int
     */
    private function sanitizePriority($priority = null)
    {
        if (is_null($priority) || !is_scalar($priority)) {
            $last = end($this->activatorPriority);
            if ($last !== false) {
                $priority = $last + 1;
            } else {
                $priority = 1;
            }

            return $priority;
        }

        return (int)$priority;
    }
}

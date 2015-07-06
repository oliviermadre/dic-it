<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:27
 */

namespace Pyrite\DI\BuildChain;

abstract class AbstractChain implements Chainable
{
    /**
     * @var Chainable
     */
    protected $next;

    /**
     * @param $serviceConfig
     * @param $serviceName
     * @param $instance
     * @return mixed|null
     */
    public function process($serviceConfig, $serviceName, $instance)
    {
        if($this->canProcess($serviceConfig)) {
            return $this->doProcess($serviceConfig, $serviceName, $instance);
        }
        elseif ($this->next) {
            return $this->next->process($serviceConfig, $serviceName, $instance);
        }
        else {
            return $instance;
        }
    }

    /**
     * @param Chainable $chain
     * @return Chainable
     */
    public function setNext(Chainable $chain)
    {
        $this->next = $chain;
        return $this->next;
    }

    /**
     * @param $serviceConfig
     * @return boolean
     */
    abstract protected function canProcess($serviceConfig);

    /**
     * @param $serviceConfig
     * @param $serviceName
     * @param $instance
     * @return mixed
     */
    abstract protected function doProcess($serviceConfig, $serviceName, $instance);
}
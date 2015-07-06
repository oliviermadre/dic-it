<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:31
 */

namespace Pyrite\DI\BuildChain;


class ForkAndForwardChain extends AbstractChain
{
    protected $chainables = array();

    public function __construct(Chainable $next = null)
    {
        $this->next = $next;
    }

    public function add(Chainable $chain)
    {
        $this->chainables[] = $chain;
    }

    protected function canProcess($serviceConfig)
    {
        foreach($this->chainables as $chainable) {
            /** @var $chainable Chainable */
            if ($chainable->canProcess($serviceConfig)) {
                return true;
            }
        }
        return false;
    }

    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        foreach($this->chainables as $chainable) {
            /** @var $chainable Chainable */
            if ($chainable->canProcess($serviceConfig)) {
                $instance = $chainable->process($serviceConfig, $serviceName, $instance);
                if($this->next) {
                    return $this->next->process($serviceConfig, $serviceName, $instance);
                }

                return $instance;
            }
        }
        return null;
    }
}
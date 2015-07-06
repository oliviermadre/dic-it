<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:32
 */

namespace Pyrite\DI\BuildChain;

use Pyrite\DI\Util\Registry;
use Pyrite\DI\Util\SimpleRegistry;

class SingletonChain implements Chainable
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var null|Chainable
     */
    protected $singletonChain = null;


    /**
     * @var null|Chainable
     */
    protected $singletonPostChain = null;

    /**
     * @var null|Chainable
     */
    protected $notSingletonChain = null;

    public function __construct(Registry $registry = null)
    {
        $this->registry = $registry ?: new SimpleRegistry();
    }

    public function setNext(Chainable $chain)
    {
        $this->notSingletonChain = $chain;
        return $this->notSingletonChain;
    }

    public function setSingletonChain(Chainable $chain)
    {
        $this->singletonChain = $chain;
        return $this->singletonChain;
    }

    public function setSingletonPostChain(Chainable $chain)
    {
        $this->singletonPostChain = $chain;
        return $this->singletonPostChain;
    }

    public function process($serviceConfig, $serviceName, $instance)
    {
        if($this->canProcess($serviceConfig)) {
            return $this->doSingletonProcess($serviceConfig, $serviceName, $instance);
        }

        return $this->doNonSingletonProcess($serviceConfig, $serviceName, $instance);
    }

    protected function canProcess($serviceConfig)
    {
        return array_key_exists('singleton', $serviceConfig) && (true == $serviceConfig['singleton']);
    }

    protected function doSingletonProcess($serviceConfig, $serviceName, $instance)
    {

        if($this->registry->has($serviceName)) {
            return $this->registry->get($serviceName);
        }

        if($this->singletonChain) {
            $instance = $this->singletonChain->process($serviceConfig, $serviceName, $instance);
            $this->registry->set($serviceName, $instance);
            if($this->singletonPostChain) {
                return $this->singletonPostChain->process($serviceConfig, $serviceName, $instance);
            }

            return $instance;
        }

        return null;
    }

    protected function doNonSingletonProcess($serviceConfig, $serviceName, $instance)
    {
        if($this->notSingletonChain) {
            return $this->notSingletonChain->process($serviceConfig, $serviceName, $instance);
        }

        return null;
    }
}
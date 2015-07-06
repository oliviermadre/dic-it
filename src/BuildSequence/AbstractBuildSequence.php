<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 17:43
 */

namespace Pyrite\DI\BuildSequence;


use Pyrite\DI\BuildChain\Chainable;
use Pyrite\DI\Container;
use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

abstract class AbstractBuildSequence implements BuildSequence {
    /**
     * @var null|Container
     */
    protected $container = null;

    /**
     * @var Chainable
     */
    protected $chain = null;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $serviceConfig
     * @param $serviceName
     * @return mixed
     * @throws \RuntimeException
     */
    final public function process($serviceConfig, $serviceName)
    {
        if(!$this->chain) {
            $this->preBuild();
        }

        if($this->chain) {
            return $this->chain->process($serviceConfig, $serviceName, null);
        }

        throw new \RuntimeException('No chain to process %s', $serviceName);
    }

    protected function preBuild()
    {
        $this->chain = $this->buildSequence($this->container->getReferenceResolverDispatcher());
        return $this;
    }

    /**
     * @param ReferenceResolverDispatcher $referenceResolverDispatcher
     * @return Chainable
     */
    abstract protected function buildSequence(ReferenceResolverDispatcher $referenceResolverDispatcher);
}
<?php

namespace Pyrite\DI;


use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use Pyrite\DI\BuildChain\Chainable;
use Pyrite\DI\BuildSequence\BuildSequence;
use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;
use Pyrite\DI\Util\Arrays;
use Pyrite\DI\Util\ParameterExpression;
use Pyrite\DI\Util\SimpleRegistry;

class ContainerImpl implements Container
{
    /**
     * @var BuildSequence
     */
    protected $buildSequence;

    /**
     * @var ReferenceResolverDispatcher
     */
    protected $referenceResolverDispatcher;

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var null|Registry
     */
    protected $bindRegistry = null;

    /**
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;
        $this->bindRegistry = new SimpleRegistry();
        $this->parameterExpression = new ParameterExpression();
    }

    /**
     * @param Chainable $chain
     */
    public function setBuildSequence(BuildSequence $sequence)
    {
        $this->buildSequence = $sequence;
    }

    /**
     * @return ReferenceResolverDispatcher
     */
    public function getReferenceResolverDispatcher()
    {
        return $this->referenceResolverDispatcher;
    }

    /**
     * @param ReferenceResolverDispatcher $referenceResolverDispatcher
     */
    public function setReferenceResolverDispatcher($referenceResolverDispatcher)
    {
        $this->referenceResolverDispatcher = $referenceResolverDispatcher;
        return $this;
    }

    public function getParameter($id)
    {
        if($this->hasParameter($id)) {
            return $this->parameterExpression->resolve($id, $this->referenceResolverDispatcher, $this->config['parameters']);
        }
        else {
            throw new \Exception($id . " not found");
        }
    }

    public function hasParameter($id)
    {
        if(array_key_exists('parameters', $this->config)) {
            return $this->parameterExpression->has($id, $this->config['parameters']);
        }

        return false;
    }

    public function get($id)
    {
        if($this->has($id)) {
            $serviceConfig = $this->config['services'][$id];
            return $this->buildSequence->process($serviceConfig, $id);
        }
        else {
            throw new \Exception($id . " not found");
        }
    }

    public function has($id)
    {
        return array_key_exists($id, $this->config['services']);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function bind($id, $object)
    {
        $this->bindRegistry->set($id, $object);
        return $this;
    }

    public function bindParameter($key, $value)
    {
        if ($this->parameterExpression->validate($key, $value)) {
            if(!array_key_exists('parameters', $this->config)) {
                $this->config['parameters'] = array();
            }

            $array = $this->parameterExpression->convertToArray($key, $value);
            $this->config['parameters'] = Arrays::merge($this->config['parameters'], $array);
        }
    }
}
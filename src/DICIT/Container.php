<?php
namespace DICIT;

use \DICIT\Util\Arrays;
use DICIT\Config\TemplatedConfigProcessor;

class Container
{
    /**
     *
     * @var mixed[]
     */
    protected $config = array();

    /**
     *
     * @var \DICIT\ArrayResolver
     */
    protected $parameters;

    /**
     *
     * @var \DICIT\ArrayResolver
     */
    protected $classes;

    /**
     *
     * @var \DICIT\Registry
     */
    protected $registry = null;

    /**
     *
     * @var \DICIT\ServiceBuilder
     */
    protected $serviceBuilder;

    /**
     *
     * @var \DICIT\ReferenceResolver
     */
    protected $referenceResolver = null;

    /**
     *
     * @param Config\AbstractConfig $cfg
     * @param ActivatorFactory $activatorFactory
     * @param InjectorFactory $injectorFactory
     */
    public function __construct(Config\AbstractConfig $cfg,
        ServiceBuilder $builder)
    {
        $this->registry = new Registry();
        $this->config = TemplatedConfigProcessor::process(new ArrayResolver($cfg->load()));

        $this->parameters = $this->config->resolve('parameters', array());
        $this->classes = $this->config->resolve('classes', array());

        $this->serviceBuilder = $builder;
        $this->referenceResolver = new ReferenceResolver($this);
    }

    public function build($definition, $serviceName = null)
    {
        if ($serviceName === null) {
            $serviceName = md5($definition . rand(0, 10000));
        }
        
        return $this->serviceBuilder->buildService($this, $serviceName, $definition);
    }
    
    /**
     * Binds an existing object or an object definition to a key in the container.
     * @param string $key The key to which the new object/definition should be bound.
     * @param mixed $item An array or an object to bind.
     * If $item is an object, it will be registered as a singleton in the
     * object registry. Otherwise, $item will be handled as an object definition.
     */
    public function bind($key, $item)
    {
        if (is_array($item)) {
            $this->classes[$key] = $item;
        }
        else {
            $this->registry->set($key, $item);
        }
    }
   
    public function lateBind($key, & $item)
    {
        if (is_array($item)) {
            $this->classes[$key] = & $item;
        }
        else {
            $this->registry->rset($key, $item);
        }
    }

    /**
     * Set a parameter in the container on any key
     * @param [type] $key   [description]
     * @param [type] $value [description]
     */
    public function setParameter($key, $value)
    {
        $path = explode('.', $key);

        $this->validateParameter($key, $value);


        $root = array();
        $r = &$root;
        foreach($path as $subNode) {
            $r[$subNode] = array();
            $r = &$r[$subNode];
        }
        $r = $value;
        $r = &$root;

        if ($this->parameters) {
            $parameters = $this->parameters->extract();
        }
        else {
            $parameters = array();
        }

        $this->parameters = new ArrayResolver(Arrays::mergeRecursiveUnique($parameters, $r));
        return $this;
    }

    /**
     * Retrieve the parameter value configured in the container
     * @param  string $parameterName
     * @return mixed
     */
    public function getParameter($parameterName)
    {
        $value = $this->parameters->resolve($parameterName);

        if ($value instanceof ArrayResolver) {
            return $value->extract();
        }

        return $value;
    }

    /**
     * Retrieve a class configured in the container
     * @param  string $serviceName
     * @return object
     */
    public function get($serviceName)
    {
        if ($this->registry->has($serviceName)) {
            return $this->registry->get($serviceName);
        }

        $serviceConfig = $this->classes->resolve($serviceName, null);

        if ($serviceConfig == null) {
            throw new \DICIT\UnknownDefinitionException($serviceName);
        }

        try {
            return $this->loadService($serviceName, $serviceConfig->extract());
        }
        catch (\DICIT\UnknownDefinitionException $ex) {
            throw new \RuntimeException(
                sprintf("Dependency '%s' not found while trying to build '%s'.",
                    $ex->getServiceName(), $serviceName));
        }
    }

    /**
     * Resolves the value of a reference key. 
     * @param string $reference
     * @return mixed
     */
    public function resolve($reference) {
        return $this->referenceResolver->resolve($reference);
    }

    /**
     * Resolves an array of references.
     * @param array $references
     * @return array containing all the resolved references
     */
    public function resolveMany(array $references = null)
    {
        if ($references === null) {
            return array();
        }

        return $this->referenceResolver->resolveMany($references);
    }

    /**
     * Flush the registry
     * @return Container
     */
    public function flushRegistry()
    {
        $this->registry->flush();
        return $this;
    }

    /**
     * Chain of command of the class loader
     * @param  array $serviceConfig
     * @param string $serviceName
     * @return object
     */
    protected function loadService($serviceName, $serviceConfig) {
        return $this->serviceBuilder->buildService($this, $serviceName, $serviceConfig);
    }

    /**
     * Check that the value to bind is a scalar, or an array multi-dimensional of scalars
     * @param  string $key
     * @param  mixed $value
     * @return boolean
     *
     * @throws IllegalTypeException
     *
     */
    protected function validateParameter($key, $value)
    {
        if (is_scalar($value)) {
            return true;
        }

        if (is_object($value)) {
            throw new IllegalTypeException(sprintf("Can't bind parameter %s with a callable", $key));
        }

        if (is_array($value)) {
            array_walk_recursive($value, function($item, $k) use($key) {
                if (!is_scalar($item)) {
                    throw new IllegalTypeException(
                        sprintf("Can't bind parameter, unauthorized value on key '%s' of '%s'", $k, $key));
                }
            });
        }

        return true;
    }
}

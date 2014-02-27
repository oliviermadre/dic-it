<?php
namespace DICIT;

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
     * @var \DICIT\ActivatorFactory
     */
    protected $activatorFactory = null;

    /**
     *
     * @var \DICIT\InjectorFactory
     */
    protected $injectorFactory = null;

    /**
     *
     * @var \DICIT\EncapsulatorFactory
     */
    protected $encapsulatorFactory = null;

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
        ActivatorFactory $activatorFactory = null, InjectorFactory $injectorFactory = null)
    {
        $this->registry = new Registry();
        $this->config = new ArrayResolver($cfg->load());

        $this->parameters = $this->config->resolve('parameters', array());
        $this->classes = $this->config->resolve('classes', array());

        $this->activatorFactory = $activatorFactory ?: new ActivatorFactory();
        $this->injectorFactory = $injectorFactory ?: new InjectorFactory();
        $this->encapsulatorFactory = new EncapsulatorFactory();
        $this->referenceResolver = new ReferenceResolver($this);
    }

    /**
     * Binds an existing object or an object definition to a key in the container.
     * @param string $key The key to which the new object/definition should be bound.
     * @param mixed $item An array or an object to bind. If $item is an object, it will be registered as a singleton in the
     * object registry. Otherwise, $item will be handled as an object definition.
     */
    public function bind($key, $item) {
        if (is_array($item)) {
            $this->classes[$key] = $item;
        } 
        else {
            $this->registry->set($key, $item);    
        }
    }
    
    /**
     * Retrieve the parameter value configured in the container
     * @param  string $parameterName
     * @return mixed
     */
    public function getParameter($parameterName) {
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
    public function get($serviceName) {
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


    public function resolve($reference) {
        return $this->referenceResolver->resolve($reference);
    }

    /**
     * Resolves an array of references.
     * @param array $references
     * @return array containing all the resolved references
     */
    public function resolveMany(array $references = null) {
        if ($references === null) {
            return array();
        }

        return $this->referenceResolver->resolveMany($references);
    }

    /**
     * Flush the registry
     * @return Container
     */
    public function flushRegistry() {
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
        $isSingleton = false;

        if (array_key_exists('singleton', $serviceConfig)) {
            $isSingleton = (bool)$serviceConfig['singleton'];
        }

        $class = $this->activate($serviceName, $serviceConfig);

        if ($isSingleton) {
            // Only store if singleton'ed to spare memory
            $this->registry->set($serviceName, $class);
        }

        $this->inject($class, $serviceConfig);
        $class = $this->encapsulate($class, $serviceConfig);

        return $class;
    }

    /**
     * Handles class instanciation
     * @param  array $serviceConfig
     * @param string $serviceName
     * @return object
     */
    protected function activate($serviceName, $serviceConfig) {
        $activator = $this->activatorFactory->getActivator($serviceName, $serviceConfig);

        return $activator->createInstance($this, $serviceName, $serviceConfig);
    }

    /**
     * Handle method invocations in the class
     * @param  object $class
     * @param  array $serviceConfig
     * @return boolean
     */
    protected function inject($class, $serviceConfig) {
        $injectors = $this->injectorFactory->getInjectors();

        foreach ($injectors as $injector) {
            $injector->inject($this, $class, $serviceConfig);
        }

        return true;
    }

    /**
     * Interceptor handler
     * @param  object $class
     * @param  array $serviceConfig
     * @return object
     */
    protected function encapsulate($class, $serviceConfig) {
        $encapsulators = $this->encapsulatorFactory->getEncapsulators();

        foreach ($encapsulators as $encapsulator) {
            $class = $encapsulator->encapsulate($this, $class, $serviceConfig);
        }

        return $class;
    }
}

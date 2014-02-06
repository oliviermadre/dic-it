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
     * @param Config\AbstractConfig $cfg
     * @param ActivatorFactory $activatorFactory
     * @param InjectorFactory $injectorFactory
     */
    public function __construct(Config\AbstractConfig $cfg,
        ActivatorFactory $activatorFactory = null, InjectorFactory $injectorFactory = null)
    {
        $this->registry = new Registry();
        $this->config = $cfg->load();
        $this->parameters = new ArrayResolver(isset($this->config['parameters']) ? $this->config['parameters'] : null);

        $this->activatorFactory = $activatorFactory ? $activatorFactory : new ActivatorFactory();
        $this->injectorFactory = $injectorFactory ? $injectorFactory : new InjectorFactory();
        $this->encapsulatorFactory = new EncapsulatorFactory();


    }

    /**
     * Retrieve the parameter value configured in the container
     * @param  string $parameterName
     * @return mixed
     */
    public function getParameter($parameterName) {
        return $this->parameters->resolve($parameterName);
    }

    /**
     * Retrieve a class configured in the container
     * @param  string $serviceName
     * @return object
     */
    public function get($serviceName) {
        if (count($this->config) > 0) {
            if (array_key_exists('classes', $this->config) &&
                array_key_exists($serviceName, $this->config['classes'])) {
                try {
                    return $this->loadService($serviceName, $this->config['classes'][$serviceName]);
                }
                catch (\DICIT\UnknownDefinitionException $ex) {
                    throw new \RuntimeException(
                        sprintf("Dependency '%s' not found while trying to build '%s'.",
                            $ex->getServiceName(), $serviceName));
                }
            }
            else {
                throw new \DICIT\UnknownDefinitionException($serviceName);
            }
        }
        else {
            throw new \RuntimeException('Container not loaded');
        }
    }

    public function resolve($reference) {
        return $this->convertValue($reference);
    }

    public function resolveMany(array $references = null) {
        if ($references === null) {
            return array();
        }

        return $this->convertParameters($references);
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

        if ($isSingleton && $this->registry->get($serviceName)) {
            return $this->registry->get($serviceName);
        }
        else {
            $class = $this->activate($serviceName, $serviceConfig);

            if ($isSingleton) {
                // Only store if singleton'ed to spare memory
                $this->registry->set($serviceName, $class);
            }

            $this->inject($class, $serviceConfig);
            $class = $this->encapsulate($class, $serviceConfig);

            return $class;
        }
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

    /**
     * Convert value written in YML to the corresponding variable (object, parameter or scalar)
     * @param  $value
     * @return mixed
     */
    protected function convertValue($value) {
        $prefix = substr($value, 0, 1);

        switch($prefix) {
            case '@' :
                $toReturn = $this->get(substr($value, 1));
                break;
            case '%' :
                $toReturn = $this->getParameter(substr($value, 1));
                break;
            default :
                $toReturn = $value;
                break;
        }
        return $toReturn;
    }

    /**
     * Parameters handler
     * @param  array $parameters
     * @return array
     */
    protected function convertParameters($parameters) {
        $convertedParameters = array();
        foreach($parameters as $value) {
            $convertedValue = $this->convertValue($value);
            $convertedParameters[] = $convertedValue;
        }
        return $convertedParameters;
    }
}

<?php
namespace DICIT;

class Container
{

    protected $config = array();
    protected $registry = null;
    protected $activatorFactory = null;

    public function __construct(Config\AbstractConfig $cfg) {
        $this->registry = new Registry();
        $this->config = $cfg->load();
        $this->activatorFactory = new ActivatorFactory();
    }

    /**
     * Retrieve the parameter value configured in the container
     * @param  string $parameterName
     * @return mixed
     */
    public function getParameter($parameterName) {
        $toReturn = null;
        if (array_key_exists('parameters', $this->config)) {
            $dotted = explode(".", $parameterName);

            if (count($dotted) > 1) {
                $currentDepthData = $this->config['parameters'];
                foreach($dotted as $paramKey) {
                    if (array_key_exists($paramKey, $currentDepthData)) {
                        $currentDepthData = $currentDepthData[$paramKey];
                    }
                    else {
                        return null;
                    }
                }
                return $currentDepthData;
            }
            elseif (array_key_exists($parameterName, $this->config['parameters'])) {
                $toReturn = $this->config['parameters'][$parameterName];
            }
        }

        return $toReturn;
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

    public function map(array $serviceNames = null) {
        if ($serviceNames === null) {
            return array();
        }

        return $this->convertParameters($serviceNames);
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
            $class = $this->classInstanciation($serviceName, $serviceConfig);
            $this->classProps($class, $serviceConfig);
            $this->classCalls($class, $serviceConfig);
            $classEncapsulated = $this->classInterceptor($class, $serviceConfig);
            
            if ($isSingleton) {
                // Only store if singleton'ed to spare memory
                $this->registry->set($serviceName, $classEncapsulated);
            }
            
            return $classEncapsulated;
        }
    }

    /**
     * Handles class instanciation
     * @param  array $serviceConfig
     * @return object
     */
    protected function classInstanciation($serviceName, $serviceConfig) {
        $activator = $this->activatorFactory->getActivator($serviceName, $serviceConfig);

        return $activator->createInstance($this, $serviceName, $serviceConfig);
    }

    /**
     * Handle method invocations in the class
     * @param  object $class
     * @param  array $serviceConfig
     * @return boolean
     */
    protected function classCalls($class, $serviceConfig) {
        $callConfig = array();
        if (array_key_exists('call', $serviceConfig)) {
            $callConfig = $serviceConfig['call'];
        }
        foreach($callConfig as $methodName => $parameters) {
            $convertedParameters = $this->convertParameters($parameters);
            call_user_func_array(array($class, $methodName), $convertedParameters);
        }

        return true;
    }

    /**
     * Handle properties in the class
     * @param  object $class
     * @param  array $serviceConfig
     * @return boolean
     */
    protected function classProps($class, $serviceConfig) {
        $propConfig = array();
        if (array_key_exists('props', $serviceConfig)) {
            $propConfig = $serviceConfig['props'];
        }
        foreach($propConfig as $propName => $propValue) {
            $convertedValue = $this->convertValue($propValue);
            $class->$propName = $convertedValue;
        }

        return true;
    }

    /**
     * Convert value written in YML to the corresponding variable (object, parameter or scalar)
     * @param  $value
     * @return mixed
     */
    protected function convertValue($value) {
        $toReturn = null;
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

    /**
     * Interceptor handler
     * @param  object $class
     * @param  array $serviceConfig
     * @return object
     */
    protected function classInterceptor($class, $serviceConfig) {
        $lastInterceptedClass = $class;
        if (array_key_exists('interceptor', $serviceConfig)) {
            if (is_array($serviceConfig)) {
                foreach($serviceConfig['interceptor'] as $interceptorName) {
                    $interceptor = $this->convertValue($interceptorName);
                    if (!is_object($interceptor)) {
                        throw new \RuntimeException('The interceptor ' . $interceptorName .
                            ' does not reference a known service');
                    }
                    $interceptor->setDecorated($lastInterceptedClass);
                    $lastInterceptedClass = $interceptor;
                }
            }
        }
        return $lastInterceptedClass;
    }
}

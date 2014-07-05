<?php
namespace DICIT;

class ServiceBuilder
{

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
    public function __construct(ActivatorFactory $activatorFactory, InjectorFactory $injectorFactory, EncapsulatorFactory $encapsulatorFactory)
    {
        $this->activatorFactory = $activatorFactory;
        $this->injectorFactory = $injectorFactory;
        $this->encapsulatorFactory = $encapsulatorFactory;
    }

    /**
     * Chain of command of the class loader
     * @param  array $serviceConfig
     * @param string $serviceName
     * @return object
     */
    public function buildService(Container $container, $serviceName, $serviceConfig) {
        $isSingleton = false;
    
        if (array_key_exists('singleton', $serviceConfig)) {
            $isSingleton = (bool)$serviceConfig['singleton'];
        }
    
        $class = $this->activate($container, $serviceName, $serviceConfig);
    
        if ($isSingleton) {
            // Only store if singleton'ed to spare memory
            $container->lateBind($serviceName, $class);
        }
    
        $this->inject($container, $class, $serviceConfig);
        $this->encapsulate($container, $class, $serviceConfig);
    
        return $class;
    }
    
    /**
     * Handles class instanciation
     * 
     * @param array $serviceConfig            
     * @param string $serviceName            
     * @return object
     */
    protected function activate($container, $serviceName, $serviceConfig)
    {
        $activator = $this->activatorFactory->getActivator($serviceName, $serviceConfig);
        
        return $activator->createInstance($container, $serviceName, $serviceConfig);
    }

    /**
     * Handle method invocations in the class
     * 
     * @param object $class            
     * @param array $serviceConfig            
     * @return boolean
     */
    protected function inject($container, $class, $serviceConfig)
    {
        $injectors = $this->injectorFactory->getInjectors();
        
        foreach ($injectors as $injector) {
            $injector->inject($container, $class, $serviceConfig);
        }
        
        return true;
    }

    /**
     * Interceptor handler
     * 
     * @param object $class            
     * @param array $serviceConfig            
     * @return object
     */
    protected function encapsulate($container, &$class, $serviceConfig)
    {
        $encapsulators = $this->encapsulatorFactory->getEncapsulators();
        
        foreach ($encapsulators as $encapsulator) {
            $class = $encapsulator->encapsulate($container, $class, $serviceConfig);
        }
    }
}

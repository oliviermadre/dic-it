<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 03/05/15
 * Time: 03:47
 */

namespace Pyrite\DI\BuildChain;


use Pyrite\DI\Container;
use Pyrite\DI\Util\Arrays;

class ExtendChain extends AbstractChain
{
    /**
     * @var null|Container
     */
    protected $container = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $serviceConfig
     * @return boolean
     */
    protected function canProcess($serviceConfig)
    {
        return array_key_exists('extend', $serviceConfig);
    }

    /**
     * @param $serviceConfig
     * @param $serviceName
     * @param $instance
     * @return mixed
     */
    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        $config = $this->container->getConfig();
        $currentService = $serviceName;
        $services = array($serviceName);
        while($this->hasParent($currentService)) {
            $currentService = $this->getParentService($currentService);
            $services[] = $currentService;
        }

        $reverse = array_reverse($services);
        $baseService = array_shift($reverse);
        $mergedConfiguration = $config['services'][$baseService];
        foreach($reverse as $service) {
            $extendedConfiguration = $config['services'][$service];
            $mergedConfiguration = Arrays::merge($mergedConfiguration, $extendedConfiguration);
        }

        unset($mergedConfiguration['extend']);

        if($this->next) {
            return $this->next->process($mergedConfiguration, $serviceName, $instance);
        }

        return null;
    }

    protected function getParentService($serviceName)
    {
        $config = $this->container->getConfig();
        return $config['services'][$serviceName]['extend'];
    }

    protected function hasParent($serviceName)
    {
        $config = $this->container->getConfig();
        return array_key_exists('extend', $config['services'][$serviceName]);
    }

}
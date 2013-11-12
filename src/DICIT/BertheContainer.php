<?php
namespace DICIT;

class BertheContainer extends ContainerImpl {
    public function __construct() {
        $classesPlugin = $this->addPlugin('classes', new Plugin\ClassesPlugin());
        $classPlugin = $classesPlugin->addPlugin('class', new Plugin\ClassPlugin());
        $parametersPlugin = $this->addPlugin('parameters', new Plugin\ParametersPlugin());

    }

    public function get($service) {
        return $this->getPluginByName('classes')->invoke($service);
    }

    public function getParameter($paramName) {
        return $this->getPluginByName('parameters')->invoke($paramName);
    }
}
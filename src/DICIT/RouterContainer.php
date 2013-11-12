<?php
namespace DICIT;

class RouterContainer extends ContainerImpl {
    public function __construct() {
        $routerPlugin = $this->addPlugin('router', new Plugin\RouterPlugin());
        $routePlugin = $classesPlugin->addPlugin('route', new Plugin\RoutePlugin());

    }

    public function get($service) {
        return $this->getPluginByName('router')->invoke($service);
    }

    public function getParameter($paramName) {
        return $this->getPluginByName('parameters')->invoke($paramName);
    }
}
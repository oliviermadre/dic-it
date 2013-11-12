<?php
namespace DICIT;

abstract class ContainerRepositoryAbstract {
    protected $configObject = null;
    protected $plugins = array();

    public function __construct(Config\AbstractConfig $config) {
        $this->configObject = $config;
        $config->load();
    }

    public function getConfig() {
        return $this->config->getData();
    }

    /**
     * @param $name string
     * @param PluginAbstractPlugin $plugin
     */
    public function addPlugin($name, Plugin\AbstractPlugin $plugin) {
        $this->plugins[$name] = $plugin;
        $plugin->setRepository($this);
        return $plugin;
    }

    /**
     * @param  string $name
     * @return ContainerAbstract
     */
    public function removePlugin($name) {
        if ($this->getPluginByName($name) !== null) {
            unset($this->plugins[$name]);
        }
        return $this;
    }

    /**
     * @param  string $name
     * @return Plugin\AbstractPlugin
     */
    public function getPluginByName($name) {
        if (array_key_exists($name, $this->plugins)) {
            return $this->plugins[$name];
        }
        else {
            return null;
        }
    }
}
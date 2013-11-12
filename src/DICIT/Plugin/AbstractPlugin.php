<?php
namespace DICIT\Plugin;

abstract class AbstractPlugin {
    protected $plugins = array();
    protected $config = null;

    public function __construct() {

    }

    public function addPlugin(AbstractPlugin $plugin) {
        $this->plugins[] = $plugin;
    }

    public function setConfig(array $config = array()) {
        $this->config = $config;
        return $this;
    }

    protected function getConfig() {
        return $this->config;
    }

    abstract public function invoke($arg);
}
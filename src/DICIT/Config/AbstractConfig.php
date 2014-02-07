<?php
namespace DICIT\Config;

abstract class AbstractConfig
{
    protected $data = array();

    public function load($force = false) {
        if ($force || empty($this->data)) {
            $this->data = $this->doLoad();
        }

        return $this->data;
    }

    public function getData() {
        return $this->data;
    }

    abstract protected function doLoad();
}

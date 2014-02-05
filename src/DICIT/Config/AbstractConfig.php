<?php
namespace DICIT\Config;

abstract class AbstractConfig
{
    protected $data = array();

    public function load($force = false) {
        if ($force) {
            $ret = $this->doLoad();
            $this->data = $ret;
        }
        elseif (!$force && count($this->data) === 0) {
            $ret = $this->doLoad();
            $this->data = $ret;
        }

        return $ret;
    }

    public function getData() {
        return $this->data;
    }

    abstract protected function doLoad();
}

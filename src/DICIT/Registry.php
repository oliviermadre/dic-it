<?php
class DICIT_Registry {
    protected $data = array();

    public function __construct() {

    }

    public function flush() {
        $this->data = array();
        return $this;
    }

    public function get($key, $throwIfNotFound = false) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        else if ($throwIfNotFound) {
            throw new RuntimeException('Key ' . $key . ' not found in DI Container registry');
        }
        else {
            return null;
        }
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }
}
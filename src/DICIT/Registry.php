<?php
namespace DICIT;

class Registry
{
    protected $data = array();

    public function __construct() {

    }

    /**
     * Flush all stored instances from the registry.
     * @return \DICIT\Registry
     */
    public function flush() {
        $this->data = array();
        return $this;
    }

    /**
     * Fetches an object from the registry.
     * @param string $key
     * @return mixed
     */
    public function get($key, $throwIfNotFound = false) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        else if ($throwIfNotFound) {
            throw new \RuntimeException('Key ' . $key . ' not found in DI Container registry');
        }
        else {
            return null;
        }
    }

    /**
     * Stores an object instance in the registry.
     * @param string $key
     * @param mixed $value
     */
    public function set($key, & $value) {
        $this->data[$key] = & $value;
    }
}

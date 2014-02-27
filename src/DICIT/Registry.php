<?php
namespace DICIT;

/**
 * Registry for storing built object instances.
 * @author Olivier Madre
 * @author Thibaud Fabre
 *
 */
class Registry
{
    protected $data = array();

    /**
     * Flush all stored instances from the registry.
     * @return \DICIT\Registry
     */
    public function flush() {
        $this->data = array();
    }

    /**
     * Fetches an object from the registry.
     * @param string $key
     * @return mixed
     */
    public function get($key, $throwIfNotFound = false) {
        if ($this->has($key)) {
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
     * Returns a boolean indicating whether there is an object associated to a given key in the registry. 
     * @param string $key
     * @return boolean
     */
    public function has($key) {
        return array_key_exists($key, $this->data);
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

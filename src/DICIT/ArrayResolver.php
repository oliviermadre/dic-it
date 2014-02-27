<?php
namespace DICIT;

class ArrayResolver implements \Iterator, \Countable, \ArrayAccess
{

    private $source;

    public function __construct(array $source = null)
    {
        if ($source == null) {
            $source = array();
        }

        $this->source = $source;
    }

    public function extract()
    {
        return $this->source;
    }

    /**
     * Resolves a value stored in an array, optionally by using dot notation to access nested elements.
     *
     * @param string $key
     *            The key value to resolve.
     * @param mixed $default
     * @return mixed The resolved value or the provided default value.
     */
    public function resolve($key, $default = null)
    {
        $toReturn = $default;
        $dotted = explode(".", $key);

        if (count($dotted) > 1) {
            $currentDepthData = $this->source;

            foreach ($dotted as $paramKey) {
                if (array_key_exists($paramKey, $currentDepthData)) {
                    $currentDepthData = $currentDepthData[$paramKey];
                } else {
                    return $this->wrapIfNecessary($default);
                }
            }

            return $this->wrapIfNecessary($currentDepthData);
        }
        elseif (array_key_exists($key, $this->source)) {
            $toReturn = $this->source[$key];
        }

        return $this->wrapIfNecessary($toReturn);
    }

    private function wrapIfNecessary($value)
    {
        if (is_array($value)) {
            return new static($value);
        }

        return $value;
    }

    public function rewind()
    {
        reset($this->source);
    }

    public function current()
    {
        return $this->wrapIfNecessary(current($this->source));
    }

    public function key()
    {
        return key($this->source);
    }

    public function next()
    {
        return next($this->source);
    }

    public function valid()
    {
        $key = key($this->source);

        return ($key !== null && $key !== false);
    }

    public function count()
    {
        return count($this->source);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->source[] = $value;
        } else {
            $this->source[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->source[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->source[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->source[$offset]) ? $this->source[$offset] : null;
    }
}

<?php
namespace DICIT;

class ArrayResolver
{

    private $source;

    public function __construct(array $source = null)
    {
        if ($source == null) {
            $source = array();
        }

        $this->source = $source;
    }

    public function resolve($key, $default = null)
    {
        $toReturn = $default;
        $dotted = explode(".", $key);

        if (count($dotted) > 1) {
            $currentDepthData = $this->source;

            foreach ($dotted as $paramKey) {
                if (array_key_exists($paramKey, $currentDepthData)) {
                    $currentDepthData = $currentDepthData[$paramKey];
                }
                else {
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

    private function wrapIfNecessary($value) {
        if (is_array($value)) {
            return new static($value);
        }

        return $value;
    }
}

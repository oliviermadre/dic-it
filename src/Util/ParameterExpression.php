<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 23:14
 */

namespace Pyrite\DI\Util;


use DICIT\IllegalTypeException;
use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

class ParameterExpression {
    public function has($key, array $haystack = array())
    {
        $nodeValue = $this->getNode($key, $haystack);
        if (null !== $nodeValue) {
            return true;
        }

        return false;
    }

    protected function getNode($key, array $haystack = array())
    {
        if (false === strpos($key, '.')) {
            if (array_key_exists($key, $haystack)) {
                return $haystack[$key];
            }
            else {
                return null;
            }
        }
        else {
            $explode = explode('.', $key);

            $ref = &$haystack;
            foreach($explode as $subKey) {
                if(!is_array($ref)) {
                    return null;
                }

                if(array_key_exists($subKey, $ref)) {
                    $ref = &$ref[$subKey];
                }
                else {
                    return null;
                }
            }

            return $ref;
        }
    }

    /**
     * @param $key
     * @param ReferenceResolverDispatcher $referenceResolver
     * @param array $haystack
     * @return mixed
     */
    public function resolve($key, ReferenceResolverDispatcher $referenceResolver, array $haystack = array())
    {
        $nodeValue = $this->getNode($key, $haystack);

        if(is_array($nodeValue)) {
            array_walk_recursive($nodeValue, function(&$item, &$key) use ($referenceResolver) {
                $item = $referenceResolver->resolve($item);
            });

            return $nodeValue;
        }
        else {
            return $referenceResolver->resolve($nodeValue);
        }
    }

    /**
     * Check that the value to bind is a scalar, or an array multi-dimensional of scalars
     * @param  string $key
     * @param  mixed $value
     * @return boolean
     *
     * @throws IllegalTypeException
     *
     */
    public function validate($key, $value)
    {
        if (is_scalar($value)) {
            return true;
        }

        if (is_object($value)) {
            throw new IllegalTypeException(sprintf("Can't bind parameter %s with a callable", $key));
        }

        if (is_array($value)) {
            array_walk_recursive($value, function($item, $k) use($key) {
                if (!is_scalar($item)) {
                    throw new IllegalTypeException(
                        sprintf("Can't bind parameter, unauthorized value on key '%s' of '%s'", $k, $key));
                }
            });
        }

        return true;
    }

    public function convertToArray($key, $value)
    {
        $path = explode('.', $key);

        $root = array();
        $r = &$root;
        foreach($path as $subNode) {
            $r[$subNode] = array();
            $r = &$r[$subNode];
        }
        $r = $value;
        $r = &$root;

        return $r;
    }

    public function merge(array &$array1, array $array2)
    {

    }
}
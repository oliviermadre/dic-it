<?php
namespace DICIT\Generator;

use DICIT\Container;
use DICIT\ReferenceResolver;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Configuration;

class BaseServiceLocator extends Container
{
    protected $registry = array();

    protected $nameUtilGenerator;

    public function __construct()
    {
        $this->referenceResolver = new ReferenceResolver($this);
        $this->registry = new Registry();
        $this->nameUtilGenerator = new NameGeneratorUtil();

        $this->lazyConfig = new Configuration();
        spl_autoload_register($this->lazyConfig->getProxyAutoloader());
    }

    protected function getNode($key, array $haystack = array())
    {
        if (false === strpos($key, '.')) {
            if (array_key_exists($key, $haystack)) {
                return $haystack[$key];
            } else {
                return null;
            }
        } else {
            $explode = explode('.', $key);

            $ref = &$haystack;
            foreach ($explode as $subKey) {
                if (!is_array($ref)) {
                    return null;
                }

                if (array_key_exists($subKey, $ref)) {
                    $ref = &$ref[$subKey];
                } else {
                    return null;
                }
            }

            return $ref;
        }
    }

    public function bind($key, $obj)
    {
        $this->registry->set($key, $obj);
        return $this;
    }

    public function setParameter($key, $value)
    {
        $converted = $this->convertToArray($key, $value);
        $this->parameters = self::merge($this->parameters, $converted);
    }

    public function convertToArray($key, $value)
    {
        $path = explode('.', $key);

        $root = array();
        $r = &$root;
        foreach ($path as $subNode) {
            $r[$subNode] = array();
            $r = &$r[$subNode];
        }
        $r = $value;
        $r = &$root;

        return $r;
    }

    public static function merge(array $array1 = array(), array $array2 = array())
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged [$key])) {
                $merged[$key] = self::merge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    protected function pushInRegistry($key, $obj)
    {
        if (!$this->hasInRegistry($key)) {
            $this->registry->set($key, $obj);
        }

        return $this;
    }

    protected function hasInRegistry($key)
    {
        return $this->registry->has($key);
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->registry)) {
            return $this->registry[$key];
        }

        $method = $this->nameUtilGenerator->getter($key);
        return $this->$method();
    }

    public function __call($method, $args)
    {
        if (strpos($method, 'get') === 0 && count($args) === 0) {
            $service = substr($method, 3);
            if ($this->hasInRegistry($service)) {
                return $this->registry->get($service);
            }
        }

        throw new \Exception(sprintf("Method not found %s", $method));
    }

    public function getParameter($key)
    {
        return $this->getNode($key, $this->parameters);
    }

    public function getLazyInstance($key, $className, $singletonCheck)
    {
        if ($singletonCheck && $this->hasInRegistry($key)) {
            return $this->registry[$key];
        } else {
            $factory = new LazyLoadingValueHolderFactory($this->getLazyConfig());
            $container = $this;
            $instance = $factory->createProxy(
                $className,
                function (& $instance, $proxy, $method, $parameters, & $initializer) use ($container, $key) {
                    $instance = $container->get($key);
                    $initializer = null;
                    return true;
                }
            );

            if ($singletonCheck) {
                $this->registry->set($key, $instance);
            }

            return $instance;
        }
    }
}

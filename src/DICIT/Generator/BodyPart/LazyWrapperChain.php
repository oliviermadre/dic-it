<?php

namespace DICIT\Generator\BodyPart;

class LazyWrapperChain implements BodyPart
{
    /**
     * @var BodyPart
     */
    protected $next;

    /**
     * @var BodyPart
     */
    protected $wrapped;


    public function handle($serviceName, $serviceConfig)
    {
        $code = "";

        if ($this->wrapped) {
            $code = $this->wrapped->handle($serviceName, $serviceConfig);
        }

        if ($this->isLazy($serviceConfig)) {
            $code = $this->generate($serviceConfig, $code);
        }

        if ($this->next) {
            $code .= $this->next->handle($serviceName, $serviceConfig);
        }

        return $code;
    }

    private function isLazy($serviceConfig)
    {
        return array_key_exists('lazy', $serviceConfig) && (bool) $serviceConfig['lazy'];
    }

    private function generate($serviceConfig, $code)
    {
        $classname = '\\' . ltrim(array_key_exists('class', $serviceConfig) ? $serviceConfig['class'] : "", '\\');

        return <<<PHP
\$factory = new \ProxyManager\Factory\LazyLoadingValueHolderFactory(\$this->getLazyConfig());
\$container = \$this;
\$instance = \$factory->createProxy('$classname',
    function (& \$instance, \$proxy, \$method, \$parameters, & \$initializer) use (\$container)
    {
        $code

        \$initializer = null;
        return true;
    });

PHP;
    }

    public function setWrappedChain(BodyPart $part)
    {
        $this->wrapped = $part;
    }

    public function setNext(BodyPart $part)
    {
        $this->next = $part;
        return $this->next;
    }
}

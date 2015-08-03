<?php

namespace DICIT\Generator\BodyPart;

class SingletonGetChain implements BodyPart
{
    /**
     * @var BodyPart
     */
    protected $next;

    public function handle($serviceName, $serviceConfig)
    {
        $code = "";

        if ($this->isSingleton($serviceConfig)) {
            $code = $this->generate($serviceName);
        }

        if ($this->next) {
            $code .= $this->next->handle($serviceName, $serviceConfig);
        }

        return $code;
    }

    private function isSingleton($serviceConfig)
    {
        return array_key_exists('singleton', $serviceConfig) && (bool) $serviceConfig['singleton'];
    }

    private function generate($serviceName)
    {
        return <<<PHP
if (\$this->registry->has('$serviceName')) {
    return \$this->registry->get('$serviceName');
}

PHP;
    }

    public function setNext(BodyPart $part)
    {
        $this->next = $part;
        return $this->next;
    }
}
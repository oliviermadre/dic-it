<?php

namespace DICIT\Generator\BodyPart;

class InitializationChain implements BodyPart
{
    /**
     * @var BodyPart
     */
    protected $next;

    public function handle($serviceName, $serviceConfig)
    {
        $code = <<<PHP
\$container = \$this;


PHP;

        if ($this->next) {
            $code .= $this->next->handle($serviceName, $serviceConfig);
        }

        return $code;
    }

    public function setNext(BodyPart $part)
    {
        $this->next = $part;
        return $this->next;
    }
}
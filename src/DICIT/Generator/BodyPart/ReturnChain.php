<?php

namespace DICIT\Generator\BodyPart;

class ReturnChain implements BodyPart
{
    /**
     * @var BodyPart
     */
    protected $next;

    public function handle($serviceName, $serviceConfig)
    {
        $code = <<<PHP
return \$instance;

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
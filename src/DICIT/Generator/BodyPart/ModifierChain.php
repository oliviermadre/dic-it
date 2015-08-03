<?php

namespace DICIT\Generator\BodyPart;

use DICIT\Generator\Modifier\Modifier;
use DICIT\Generator\ModifierFactory;

class ModifierChain implements BodyPart
{
    /**
     * @var BodyPart
     */
    protected $next;

    protected $modifierFactory = null;

    public function __construct(ModifierFactory $factory)
    {
        $this->modifierFactory = $factory;
    }

    public function handle($serviceName, $serviceConfig)
    {
        $code = $this->generate($serviceName, $serviceConfig);

        if ($this->next) {
            $code .= $this->next->handle($serviceName, $serviceConfig);
        }

        return $code;
    }

    protected function generate($serviceName, $serviceConfig)
    {
        /** @var Modifier[] $modifiers */
        $modifiers = $this->modifierFactory->get($serviceConfig);
        $modifierCode = "";
        foreach ($modifiers as $modifier) {
            $modifierCode .= $modifier->modify($serviceName, $serviceConfig);
        }

        return $modifierCode;
    }

    public function setNext(BodyPart $part)
    {
        $this->next = $part;
        return $this->next;
    }
}
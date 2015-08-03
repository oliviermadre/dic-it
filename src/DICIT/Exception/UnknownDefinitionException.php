<?php

namespace DICIT\Exception;

class UnknownDefinitionException extends \RuntimeException
{
    private $serviceName;

    /**
     * @param string $serviceName
     */
    public function __construct($serviceName)
    {
        parent::__construct('Class not configured : ' . $serviceName);

        $this->serviceName = $serviceName;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }
}

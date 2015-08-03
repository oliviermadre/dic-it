<?php

namespace DICIT\Generator\BodyPart;

interface BodyPart
{
    /**
     * @param $serviceName
     * @param $serviceConfig
     * @return string generated code
     */
    public function handle($serviceName, $serviceConfig);

    /**
     * Set the next element in chain, returns the next element for chaining convenience
     * @param BodyPart $part
     * @return BodyPart
     */
    public function setNext(BodyPart $part);
}
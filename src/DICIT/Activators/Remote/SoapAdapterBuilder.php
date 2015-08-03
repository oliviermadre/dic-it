<?php

namespace DICIT\Activators\Remote;

use ProxyManager\Factory\RemoteObject\Adapter\Soap;
use Zend\Soap\Client as SoapClient;

class SoapAdapterBuilder implements RemoteAdapterBuilder
{
    public function build($serviceName, array $serviceConfig)
    {
        $endpoint = $serviceConfig['endpoint'];

        return new Soap(new SoapClient($endpoint));
    }

    public function canBuild($protocol)
    {
        return strtolower($protocol) === 'soap';
    }
}
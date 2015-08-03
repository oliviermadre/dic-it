<?php

namespace DICIT\Activators\Remote;

use ProxyManager\Factory\RemoteObject\Adapter\XmlRpc;
use Zend\XmlRpc\Client;

class XmlRpcAdapterBuilder implements RemoteAdapterBuilder
{
    public function build($serviceName, array $serviceConfig)
    {
        $endpoint = $serviceConfig['endpoint'];

        return new XmlRpc(new Client($endpoint));
    }

    public function canBuild($protocol)
    {
        return strtolower($protocol) === 'xml-rpc';
    }
}
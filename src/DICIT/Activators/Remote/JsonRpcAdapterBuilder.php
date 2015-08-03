<?php

namespace DICIT\Activators\Remote;

use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Zend\Json\Server\Client;

class JsonRpcAdapterBuilder implements RemoteAdapterBuilder
{
    public function build($serviceName, array $serviceConfig)
    {
        $endpoint = $serviceConfig['endpoint'];

        return new JsonRpc(new Client($endpoint));
    }

    /**
     * @param $protocol
     * @return boolean
     */
    public function canBuild($protocol)
    {
        return strtolower($protocol) === 'json-rpc';
    }
}
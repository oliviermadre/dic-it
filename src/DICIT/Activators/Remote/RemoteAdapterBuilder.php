<?php

namespace DICIT\Activators\Remote;

use ProxyManager\Factory\RemoteObject\AdapterInterface;

interface RemoteAdapterBuilder
{
    /**
     * Build and Return an AdapterInterface for the requested service
     * @param $serviceName
     * @param array $serviceConfig
     * @return AdapterInterface
     */
    public function build($serviceName, array $serviceConfig);


    /**
     * @param $protocol
     * @return boolean
     */
    public function canBuild($protocol);
}
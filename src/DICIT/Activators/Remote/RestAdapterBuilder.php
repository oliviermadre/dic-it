<?php

namespace DICIT\Activators\Remote;

use DICIT\Activators\Remote\RestAdapter;
use Guzzle\Http\Client;

class RestAdapterBuilder implements RemoteAdapterBuilder
{
    public function build($serviceName, array $serviceConfig)
    {
        $endpoint = $serviceConfig['endpoint'];

        return new RestAdapter(new Client($endpoint));
    }

    public function canBuild($protocol)
    {
        return strtolower($protocol) === 'rest';
    }
}
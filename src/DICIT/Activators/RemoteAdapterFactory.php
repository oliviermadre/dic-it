<?php
namespace DICIT\Activators;

use DICIT\Activators\Remote\RestAdapter;

class RemoteAdapterFactory
{

    /**
     *
     * @param string $serviceName
     * @param array $serviceConfig
     * @throws \BadMethodCallException
     * @return \ProxyManager\Factory\RemoteObject\AdapterInterface
     */
    public function getAdapter($serviceName, array $serviceConfig)
    {
        if (! isset($serviceConfig['protocol']) || ! isset($serviceConfig['endpoint'])) {
            throw new \InvalidArgumentException(
                sprintf("Protocol and endpoint are required for remote object '%s'", $serviceName));
        }

        $protocol = $serviceConfig['protocol'];
        $endpoint = $serviceConfig['endpoint'];

        switch ($protocol) {
            case 'xml-rpc':
                return new \ProxyManager\Factory\RemoteObject\Adapter\XmlRpc(new \Zend\XmlRpc\Client($endpoint));
            case 'json-rpc':
                return new \ProxyManager\Factory\RemoteObject\Adapter\JsonRpc(new \Zend\Json\Server\Client($endpoint));
            case 'soap':
                return new \ProxyManager\Factory\RemoteObject\Adapter\Soap(new \Zend\Soap\Client($endpoint));
            case 'rest':
                return new RestAdapter(new \Guzzle\Http\Client($endpoint));
            default:
                throw new UnknownProtocolException(sprintf("Protocol '%s' is not supported "));
        }
    }
}

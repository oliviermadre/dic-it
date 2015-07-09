<?php
namespace DICIT\Activators;

use DICIT\Activators\Remote\RestAdapter;
use Guzzle\Http\Client as GuzzleHttpClient;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use ProxyManager\Factory\RemoteObject\Adapter\Soap;
use ProxyManager\Factory\RemoteObject\Adapter\XmlRpc;
use Zend\Json\Server\Client as JsonServerClient;
use Zend\Soap\Client as SoapClient;
use Zend\XmlRpc\Client as XmlRpcClient;

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
                return new XmlRpc(new XmlRpcClient($endpoint));
            case 'json-rpc':
                return new JsonRpc(new JsonServerClient($endpoint));
            case 'soap':
                return new Soap(new SoapClient($endpoint));
            case 'rest':
                return new RestAdapter(new GuzzleHttpClient($endpoint));
            default:
                throw new UnknownProtocolException(sprintf("Protocol '%s' is not supported ", $protocol));
        }
    }
}

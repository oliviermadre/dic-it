<?php
namespace DICIT\Activators;

use DICIT\Activators\Remote\RemoteAdapterBuilder;
use DICIT\Exception\UnknownProtocolException;
use InvalidArgumentException;
use ProxyManager\Factory\RemoteObject\AdapterInterface;

class RemoteAdapterFactory
{
    /**
     * @var RemoteAdapterBuilder[]
     */
    protected $adapterBuilders = array();

    /**
     *
     * @param string $serviceName
     * @param array $serviceConfig
     * @throws InvalidArgumentException
     * @throws UnknownProtocolException
     * @return AdapterInterface
     */
    public function getAdapter($serviceName, array $serviceConfig)
    {
        if (! isset($serviceConfig['protocol'])) {
            throw new InvalidArgumentException (
                sprintf("Protocol is required for remote object '%s'", $serviceName));
        }

        $protocol = $serviceConfig['protocol'];

        $adapterBuilder = $this->fetchAdapterBuilder($protocol);

        return $adapterBuilder->build($serviceName, $serviceConfig);
    }

    /**
     * @param $protocolName
     * @param RemoteAdapterBuilder $adapterBuilder
     * @return $this
     */
    public function addAdapterBuilder($protocolName, RemoteAdapterBuilder $adapterBuilder)
    {
        $this->adapterBuilders[$protocolName] = $adapterBuilder;
        return $this;
    }

    /**
     * @param $protocol
     * @return RemoteAdapterBuilder
     */
    protected function fetchAdapterBuilder($protocol)
    {
        // Try to use the adapter using the key of the adapter builder storage
        if (array_key_exists($protocol, $this->adapterBuilders)) {
            $adapterBuilder = $this->adapterBuilders[$protocol];
            if ($adapterBuilder->canBuild($protocol)) {
                return $adapterBuilder;
            }
        }

        // Try to find any fallback that may handle the protocol
        foreach ($this->adapterBuilders as $adapterBuilder) {
            if ($adapterBuilder->canBuild($protocol)) {
                return $adapterBuilder;
            }
        }

        throw new UnknownProtocolException(sprintf("Protocol '%s' is not supported", $protocol));
    }
}

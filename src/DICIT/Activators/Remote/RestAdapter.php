<?php

namespace DICIT\Activators\Remote;

use ProxyManager\Factory\RemoteObject\AdapterInterface;

class RestAdapter implements AdapterInterface
{

    /**
     *
     * @var \Guzzle\Http\ClientInterface
     */
    private $client;

    public function __construct(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    public function call($wrappedClass, $method, array $params = array())
    {
        $request = $this->client->post(sprintf('/%s/%s', $wrappedClass, $method), $params);

        $response = $request->send();

        return $response->getBody();
    }
}

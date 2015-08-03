<?php

namespace DICIT\Activators\Remote;

use Guzzle\Http\ClientInterface;
use ProxyManager\Factory\RemoteObject\AdapterInterface;

class RestAdapter implements AdapterInterface
{

    /**
     *
     * @var ClientInterface
     */
    private $client;

    private $auth = array();

    public function __construct(ClientInterface $client, array $auth = array())
    {
        $this->client = $client;
        $this->auth = $auth;
    }

    public function call($wrappedClass, $method, array $params = array())
    {
        $request = $this->client->post(sprintf('/%s/%s', str_replace('\\', '/', $wrappedClass), $method), $params);

        $auth = $this->getAuthConfig($this->auth);
        if ($auth) {
            $request->setAuth($auth['login'], $auth['pass'], $auth['type']);
        }

        $response = $request->send();

        return $response->getBody();
    }

    private function getAuthConfig(array $config = array())
    {
        if (count($config)) {
            $ret = array();
            $ret['type'] = $this->get($config, 'type', 'basic');
            $ret['login'] = $this->get($config, 'login', '');
            $ret['pass'] = $this->get($config, 'pass', '');
            return $ret;
        } else {
            return null;
        }
    }

    private function get(array $data, $key, $defaultValue = '')
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $defaultValue;
    }
}

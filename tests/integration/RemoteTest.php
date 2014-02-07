<?php
define('ROOT_DIR', dirname(dirname(__DIR__)));
define('TEST_DIR', ROOT_DIR . '/tests' );

// COMPOSER <3
require ROOT_DIR . '/vendor/autoload.php';
require ROOT_DIR . '/tests/integration/fixture/all.php';

// DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 'on');

interface FooServiceInterface
{
    /**
     *
     * @param string $param
     * @param string $p2
     * @return string
     */
    public function foo($param, $p2);
}

// Getting config using YML
$cfgYML = new \DICIT\Config\YML(ROOT_DIR . '/tests/integration/config/remote_test.yml');
$container = new \DICIT\Container($cfgYML);

$service = $container->get('RemoteService');
echo $service->foo('js is crap', 'real crap');
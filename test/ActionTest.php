<?php
define('ROOT_DIR', dirname(__DIR__) );
define('TEST_DIR', ROOT_DIR . '/test' );

// COMPOSER <3
require ROOT_DIR . '/vendor/autoload.php';
require ROOT_DIR . '/test/fixture/all.php';
// DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Getting config using YML
$cfgYML = new \DICIT\Config\YML(ROOT_DIR . '/test/config/container_test.yml');
$container = new \DICIT\Container($cfgYML);

var_dump($container->get('CountryService'));

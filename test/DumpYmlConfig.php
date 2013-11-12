<?php
define('ROOT_DIR', dirname(__DIR__) );
define('TEST_DIR', ROOT_DIR . '/test' );

// COMPOSER <3
require ROOT_DIR . '/vendor/autoload.php';


// Getting config using YML
$cfgYML = new \DICIT\Config\YML(ROOT_DIR . '/test/config/container_test.yml');
$dump = $cfgYML->compile();
echo $dump;

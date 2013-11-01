<?php
define('ROOT_DIR', dirname(__DIR__) );
define('TEST_DIR', ROOT_DIR . '/test' );

// COMPOSER <3
require ROOT_DIR . '/vendor/autoload.php';

// DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// requires only for testing purpose
require TEST_DIR . '/DummyFramework/Initializer.php';
require TEST_DIR . '/DummyFramework/AbstractController.php';
require TEST_DIR . '/DummyFramework/CountryControllerTest.php';
require TEST_DIR . '/DummyFramework/Berthe/Modules/Country/Manager.php';
require TEST_DIR . '/DummyFramework/Berthe/Service/Country.php';
require TEST_DIR . '/DummyFramework/Berthe/Store/Array.php';
require TEST_DIR . '/DummyFramework/Berthe/Store/Echo.php';

// Getting config using YML
$cfgYML = new DICIT_ConfigYML(ROOT_DIR . '/test/config/container_test.yml');

// Compile it in PHP to load it faster next time
$dump = $cfgYML->compile();
$dump = '<?php $array = ' . $dump . ';';
file_put_contents(ROOT_DIR . '/test/config/generated.php', $dump);

// Start the application with the PHP config file
$cfgPHP = new DICIT_ConfigPHP(ROOT_DIR . '/test/config/generated.php');
$init = new Initializer($cfgPHP);

// Go Dispatch !
$init->dispatch();

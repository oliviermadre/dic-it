<?php

/*define('ROOT_DIR', dirname(dirname(__DIR__)));
define('TEST_DIR', ROOT_DIR . '/tests' );*/

// COMPOSER <3
//require ROOT_DIR . '/vendor/autoload.php';

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

class Foo implements FooServiceInterface
{

    public function __construct()  {
        // Stub to avoid having method foo() called as constructor
    }

    /**
     * Foo function
     * @param string $param
     * @param string $p2
     * @return string
     */
    public function foo($param, $p2)
    {
        error_log('got request with param ' . $param);
        error_log(var_export($param, true));
        error_log(var_export($p2, true));

        return base64_encode($param);
    }
}

$class = 'Foo';
$object = new $class;

die('Done');

/*$server = new \Zend\XmlRpc\Server();
$server->setClass('Foo', 'FooServiceInterface');  // my FooServiceInterface implementation
$server->handle();*/
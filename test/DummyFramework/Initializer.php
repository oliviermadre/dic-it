<?php
class Initializer {
    public static $container = null;

    public function __construct($config) {
        self::$container = new DICIT_Container($config);
    }

    public function dispatch() {
        $class = new CountryControllerTest();
        $class->setContainer(self::$container);
        $class->getAction();
    }
}
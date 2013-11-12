<?php
class BertheContainerTest extends \PHPUnit_Framework_TestCase {
    protected $configPath = "";

    public function setUp() {
        $this->configPath = dirname(__DIR__) . '/config/';
    }

    public function testThatConfigHasClasses() {
        $config = new DICIT\Config\YML($this->configPath . 'container_test.yml');
        $arrayCfg = $config->load();

        $hasNode = array_key_exists("classes", $arrayCfg);
        $this->assertTrue($hasNode);
    }

    public function testThatConfigHasParameters() {
        $config = new DICIT\Config\YML($this->configPath . 'container_test.yml');
        $arrayCfg = $config->load();

        $hasNode = array_key_exists("parameters", $arrayCfg);
        $this->assertTrue($hasNode);
    }

    public function testThatContfigHasNotDummy() {
        $config = new DICIT\Config\YML($this->configPath . 'container_test.yml');
        $arrayCfg = $config->load();

        $hasNode = array_key_exists("dummy", $arrayCfg);
        $this->assertFalse($hasNode);
    }
}
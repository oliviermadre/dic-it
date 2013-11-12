<?php
class BerthePluginParametersTest extends \PHPUnit_Framework_TestCase {
    protected $configPath = "";

    public function setUp() {
        $this->configPath = dirname(__DIR__) . '/config/';
    }

    public function testThatParameterFetchLevel1Key() {
        $config = new DICIT\Config\YML($this->configPath . 'test_model.yml');
        $arrayCfg = $config->load();

        $parameters = new DICIT\Plugin\ParametersPlugin();
        $parameters->setConfig($arrayCfg);

        $value1 = $parameters->invoke('env');

        $this->assertTrue($value1 === 'dev');
        $this->assertFalse($value1 === 'prod');
    }

    public function testThatParameterFetchLevel2Key() {
        $config = new DICIT\Config\YML($this->configPath . 'test_model.yml');
        $arrayCfg = $config->load();

        $parameters = new DICIT\Plugin\ParametersPlugin();
        $parameters->setConfig($arrayCfg);

        $value1 = $parameters->invoke('database.host');
        $value2 = $parameters->invoke('database.%login_field');

        $this->assertTrue($value1 === '127.0.0.1');
        $this->assertTrue($value2 === 'evaneos');
    }

    public function testThatParameterFetchLevel3Key() {
        $config = new DICIT\Config\YML($this->configPath . 'test_model.yml');
        $arrayCfg = $config->load();

        $parameters = new DICIT\Plugin\ParametersPlugin();
        $parameters->setConfig($arrayCfg);

        $value1 = $parameters->invoke('dev.fr.var1');
        $value2 = $parameters->invoke('%env.%lang.var1');
        $value3 = $parameters->invoke('%env.%lang.%var3');

        $this->assertTrue($value1 === 'value_dev_fr_1');
        $this->assertTrue($value2 === 'value_dev_es_1');
        $this->assertTrue($value3 === 'value_dev_es_3bis');
    }
}
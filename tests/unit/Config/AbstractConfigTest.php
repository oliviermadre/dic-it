<?php

namespace DICIT\Tests;

class AbstractConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testLoadReturnsDataFromDoLoad()
    {
        $array = array('data' => 'value');

        $config = $this->getMockForAbstractClass('\DICIT\Config\AbstractConfig');

        $config->expects($this->atLeastOnce())
            ->method('doLoad')
            ->will($this->returnValue($array));

        $this->assertEquals($array, $config->load());
    }

    public function testLoadWithoutForceCachesData()
    {
        $array = array('data' => 'value');

        $config = $this->getMockForAbstractClass('\DICIT\Config\AbstractConfig');

        $config->expects($this->once())
            ->method('doLoad')
            ->will($this->returnValue($array));

        $config->load();
        $config->load();
    }

    public function testGetDataReturnsDataWithoutLoading()
    {
        $array = array('data' => 'value');

        $config = $this->getMockForAbstractClass('\DICIT\Config\AbstractConfig');

        $config->expects($this->never())
            ->method('doLoad')
            ->will($this->returnValue($array));

        $this->assertEmpty($config->getData());
    }

    public function testGetDataReturnsLoadedData()
    {
        $array = array('data' => 'value');

        $config = $this->getMockForAbstractClass('\DICIT\Config\AbstractConfig');

        $config->expects($this->any())
            ->method('doLoad')
            ->will($this->returnValue($array));

        $this->assertEquals($config->load(true), $config->getData());
    }

}

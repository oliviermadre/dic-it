<?php

namespace DICIT\Tests\Activators;

use DICIT\Activators\RemoteActivator;
class RemoteActivatorTest extends \PHPUnit_Framework_TestCase
{

    public function getInvalidConfigurations()
    {
        return array(
            array(array()),
            array(array('class' => '\DummyClass')),
            array(array('remote' => array()))
        );
    }

    /**
     * @dataProvider getInvalidConfigurations
     * @expectedException \DICIT\Exception\UnbuildableServiceException
     */
    public function testInvalidConfigurationThrowsException($serviceConfig)
    {
        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();
        $adapterFactory = $this->getMockBuilder('\DICIT\Activators\RemoteAdapterFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $activator = new RemoteActivator($adapterFactory);

        $activator->createInstance($container, 'myService', $serviceConfig);
    }

    public function testValidConfigurationReturnsInstanceOfRequestedType()
    {
        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();
        $adapterFactory = $this->getMockBuilder('\DICIT\Activators\RemoteAdapterFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceConfig = array(
            'class' => '\stdClass',
            'remote' => array('endpoint' => 'http://localhost:80', 'protocol' => 'rest')
        );

        $adapterFactory->expects($this->any())
            ->method('getAdapter')
            ->will($this->returnValue($this->getMock('\ProxyManager\Factory\RemoteObject\AdapterInterface')));

        $activator = new RemoteActivator($adapterFactory);

        $instance = $activator->createInstance($container, 'myService', $serviceConfig);

        $this->assertInstanceOf('\stdClass', $instance);
    }
}

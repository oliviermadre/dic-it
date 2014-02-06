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
     * @expectedException \DICIT\UnbuildableServiceException
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

}

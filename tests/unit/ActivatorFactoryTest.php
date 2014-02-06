<?php
namespace DICIT\Tests;

use DICIT\ActivatorFactory;

class ActivatorFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function getInvalidServiceConfigurations()
    {
        return array(
            array(array()),
            array(array('class' => '\DummyClass', 'builder' => '\invalidBuilderDefinition'))
        );
    }

    /**
     * @dataProvider getInvalidServiceConfigurations
     * @expectedException \DICIT\UnbuildableServiceException
     */
    public function testGetActivatorThrowsExceptionForInvalidConfigurations($serviceConfig)
    {
        $factory = new ActivatorFactory();

        $factory->getActivator('myService', $serviceConfig);
    }

    public function testGetActivatorWithStaticBuilderConfigReturnsStaticActivator()
    {
        $serviceConfig = array('class' => '\DummyClass', 'builder' => '\DummyBuilder::dummyFactoryMethod');

        $factory = new ActivatorFactory();

        $this->assertInstanceOf('\DICIT\Activators\StaticInvocationActivator',
            $factory->getActivator('myService', $serviceConfig));
    }

    public function testGetActivatorWithInstanceBuilderConfigReturnsInstanceActivator()
    {
        $serviceConfig = array('class' => '\DummyClass', 'builder' => '@DummyBuilder->dummyFactoryMethod');

        $factory = new ActivatorFactory();

        $this->assertInstanceOf('\DICIT\Activators\InstanceInvocationActivator',
            $factory->getActivator('myService', $serviceConfig));
    }
}

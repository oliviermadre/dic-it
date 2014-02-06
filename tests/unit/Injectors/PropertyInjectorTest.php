<?php

namespace DICIT\Tests\Injectors;

use DICIT\Injectors\PropertyInjector;

class PropertyInjectorTest extends \PHPUnit_Framework_TestCase
{
    public function testDefinedMethodsAreInvoked()
    {
        $mock = new \stdClass();

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->setMethods(array('resolve'))
            ->getMock();

        $container->expects($this->once())
            ->method('resolve')
            ->will($this->returnValue(2));

        $injector = new PropertyInjector();
        $serviceConfig = array('props' => array('property' => array('value')));

        $injector->inject($container, $mock, $serviceConfig);

        $this->equalTo(2, $mock->property);
    }
}

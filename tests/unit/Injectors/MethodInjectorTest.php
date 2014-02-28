<?php
namespace DICIT\Tests\Injectors;

use DICIT\Injectors\MethodInjector;

class MethodInjectorTest extends \PHPUnit_Framework_TestCase
{

    public function testDefinedMethodsAreInvoked()
    {
        $mock = $this->getMock('\DICIT\Tests\Injectors\TestInjectable');

        $mock->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo(2));

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->setMethods(array('resolveMany'))
            ->getMock();

        $container->expects($this->once())
            ->method('resolveMany')
            ->will($this->returnValue(array(2)));

        $injector = new MethodInjector();

        $serviceConfig = array('call' => array('setMethod' => array('value')));

        $actual = $injector->inject($container, $mock, $serviceConfig);
    }

    public function testArrayOfParameterArraysInvokesInjectionMultipleTimes()
    {
        $mock = $this->getMock('\DICIT\Tests\Injectors\TestInjectable');

        $mock->expects($this->exactly(2))
            ->method('setMethod')
            ->with($this->equalTo(2));

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->setMethods(array('resolveMany'))
            ->getMock();

        $container->expects($this->any())
            ->method('resolveMany')
            ->will($this->returnValue(array(2)));

        $injector = new MethodInjector();

        $serviceConfig = array('call' => array('setMethod[0]' => array('value'), 'setMethod[1]' => array('value2')));

        $actual = $injector->inject($container, $mock, $serviceConfig);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMalformedMethodNameThrowsRuntime()
    {
        $mock = $this->getMock('\DICIT\Tests\Injectors\TestInjectable');

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->setMethods(array('resolveMany'))
            ->getMock();

        $injector = new MethodInjector();

        $serviceConfig = array('call' => array('setMethod[abc' => array('value')));

        $actual = $injector->inject($container, $mock, $serviceConfig);
    }

}

class TestInjectable
{

    public function setMethod($value)
    {}
}

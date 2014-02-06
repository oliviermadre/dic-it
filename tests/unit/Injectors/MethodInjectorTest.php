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
}

class TestInjectable {
    
    public function setMethod($value) {
        
    }
    
}
<?php

namespace DICIT\Tests\Encapsulators;

use DICIT\Encapsulators\InterceptorEncapsulator;

class InterceptorEncapsulatorTest extends \PHPUnit_Framework_TestCase
{
    public function testEncapsulateWithNoInterceptorConfigurationReturnsSameObject()
    {
        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceConfig = array();

        $encapsulator = new InterceptorEncapsulator();
        $object = new \stdClass();

        $this->assertSame($object, $encapsulator->encapsulate($container, $object, $serviceConfig));
    }

    public function testEncapsulateWithInterceptorsReturnsDifferentObjects()
    {
        $interceptor = new DummyInterceptor();

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $container->expects($this->atLeastOnce())
            ->method('resolve')
            ->with($this->equalTo('@myInterceptor'))
            ->will($this->returnValue($interceptor));

        $serviceConfig = array('interceptor' => array('@myInterceptor'));

        $encapsulator = new InterceptorEncapsulator();
        $object = new \stdClass();

        $this->assertSame($interceptor, $encapsulator->encapsulate($container, $object, $serviceConfig));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEncapsulateWithInvalidInterceptorsThrowsException()
    {
        $interceptor = true;

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $container->expects($this->atLeastOnce())
        ->method('resolve')
        ->with($this->equalTo('@myInterceptor'))
        ->will($this->returnValue($interceptor));

        $serviceConfig = array('interceptor' => array('@myInterceptor'));

        $encapsulator = new InterceptorEncapsulator();
        $object = new \stdClass();

        $encapsulator->encapsulate($container, $object, $serviceConfig);
    }
}

class DummyInterceptor
{
    public function setDecorated($object) {

    }
}

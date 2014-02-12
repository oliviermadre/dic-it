<?php
namespace DICIT\Tests\Activators;

use DICIT\Activators\LazyActivator;

class LazyActivatorTest extends \PHPUnit_Framework_TestCase
{

    public function testLazyActivatorDelegatesToRealActivatorWhenLazyIsDisabledOrMissing()
    {
        $baseActivator = $this->getMock('\DICIT\Activator');

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $config = array('');

        $baseActivator->expects($this->exactly(2))
            ->method('createInstance');

        $activator = new LazyActivator($baseActivator);

        $activator->createInstance($container, 'service', $config);

        $config = array('lazy' => false);

        $activator->createInstance($container, 'service', $config);
    }

    public function testLazyActivatorDoesNotInvokeRealActivatorWhenLazyIsEnabled()
    {
        $baseActivator = $this->getMock('\DICIT\Activator');

        $container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $config = array('lazy' => true, 'class' => '\stdClass');

        $baseActivator->expects($this->never())
            ->method('createInstance');

        $activator = new LazyActivator($baseActivator);
        $activator->createInstance($container, 'service', $config);
    }

    public function testRealActivatorisInvokedWhenLazyObjectIsUsed()
    {
        $baseActivator = $this->getMock('\DICIT\Activator');

        $container = $this->getMockBuilder('\DICIT\Container')
        ->disableOriginalConstructor()
        ->getMock();

        $config = array('lazy' => true, 'class' => '\DICIT\Tests\Activators\LazyActivatorTestClass');

        $activator = new LazyActivator($baseActivator);
        $instance = $activator->createInstance($container, 'service', $config);

        $baseActivator->expects($this->atLeastOnce())
            ->method('createInstance');

        echo $instance->hello;
    }
}

class LazyActivatorTestClass
{
    public $hello;
}

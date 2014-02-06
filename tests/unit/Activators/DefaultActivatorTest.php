<?php
namespace DICIT\Tests\Activators;

use DICIT\Activators\DefaultActivator;

class DefaultInvocationActivatorTest extends \PHPUnit_Framework_TestCase
{

    protected $container;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @expectedException \DICIT\UnbuildableServiceException
     */
    public function testActicationFailsWithMissingClass()
    {
        $activator = new DefaultActivator();
        
        $instance = $activator->createInstance($this->container, 'dependency', array(
            'class' => '\DICIT\UnpossiblyFindableClass'
        ));
    }

    public function testActivationSucceedsWithNoArgs()
    {
        $activator = new DefaultActivator();
        
        $instance = $activator->createInstance($this->container, 'dependency', array(
            'class' => '\stdClass'
        ));
        
        $this->assertNotNull($instance);
        $this->assertInstanceOf('\stdClass', $instance);
    }

    public function testActivationSucceedsWithArgs()
    {
        $this->container->expects($this->once())
            ->method('resolveMany')
            ->will($this->returnArgument(0));
        
        $activator = new DefaultActivator();
        
        $instance = $activator->createInstance($this->container, 'dependency', array(
            'class' => '\DICIT\Tests\Activators\TestConstructorInjectable',
            'arguments' => array(
                2
            )
        ));
        
        $this->assertNotNull($instance);
        $this->assertInstanceOf('\DICIT\Tests\Activators\TestConstructorInjectable', $instance);
    }
}

class TestConstructorInjectable
{

    public $constructorInjectedValue = 0;

    public function __construct($value)
    {
        $this->constructorInjectedValue = $value;
    }
}
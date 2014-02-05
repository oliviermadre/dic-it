<?php
namespace DICIT\Tests\Activators;

use DICIT\Activators\InstanceInvocationActivator;

class InstanceInvocationActivatorTest extends \PHPUnit_Framework_TestCase
{

    protected $service;

    protected $container;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new \stdClass();
    }

    public function stubFactoryMethod()
    {
        return $this->service;
    }

    /**
     * @expectedException \DICIT\UnbuildableServiceException
     */
    public function testActicationFailsWithMissingMethod()
    {
        $this->container->expects($this->any())
            ->method('get')
            ->with($this->equalTo('provider'))
            ->will($this->returnValue($this));

        $activator = new InstanceInvocationActivator();

        $instance = $activator->createInstance($this->container, 'dependency',
            array('builder' => 'provider->unknownFactoryMethod'));

        $this->assertSame($this->service, $instance);
    }

    public function testActivationSucceedsWithNoArgs()
    {
        $this->container->expects($this->any())
            ->method('get')
            ->with($this->equalTo('provider'))
            ->will($this->returnValue($this));

        $activator = new InstanceInvocationActivator();

        $instance = $activator->createInstance($this->container, 'dependency',
            array('builder' => 'provider->stubFactoryMethod'));

        $this->assertSame($this->service, $instance);
    }
}
<?php
namespace DICIT\Tests\Activators;

use DICIT\Activators\StaticInvocationActivator;

class StaticInvocationActivatorTest extends \PHPUnit_Framework_TestCase
{

    protected static $service;

    protected $container;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        self::$service = new \stdClass();
    }

    public static function stubFactoryMethod()
    {
        return self::$service;
    }

    /**
     * @expectedException \DICIT\UnbuildableServiceException
     */
    public function testActicationFailsWithMissingClass()
    {
        $activator = new StaticInvocationActivator();

        $instance = $activator->createInstance($this->container, 'dependency',
            array('builder' => '\DICIT\UnpossiblyFindableClass::unknownFactoryMethod'));
    }

    /**
     * @expectedException \DICIT\UnbuildableServiceException
     */
    public function testActicationFailsWithMissingMethod()
    {
        $activator = new StaticInvocationActivator();

        $instance = $activator->createInstance($this->container, 'dependency',
            array('builder' => '\DICIT\Tests\Activators\StaticInvocationActivatorTest::unknownFactoryMethod'));
    }

    public function testActivationSucceedsWithNoArgs()
    {
        $activator = new StaticInvocationActivator();

        $instance = $activator->createInstance($this->container, 'dependency',
            array('builder' => '\DICIT\Tests\Activators\StaticInvocationActivatorTest::stubFactoryMethod'));

        $this->assertSame(self::$service, $instance);
    }
}

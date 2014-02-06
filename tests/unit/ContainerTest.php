<?php

namespace DICIT\Tests;

use DICIT\Container;
use DICIT\ActivatorFactory;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \DICIT\UnknownDefinitionException
     */
    public function testContainerThrowsExceptionOnMissingServiceName()
    {
        $config = $this->getMockBuilder('\DICIT\Config\AbstractConfig')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $config->expects($this->any())
            ->method('doLoad')
            ->will($this->returnValue(array()));

        $container = new Container($config);

        $container->get('UnknownService');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testContainerThrowsExceptionOnMissingDependency()
    {
        $config = $this->getMockBuilder('\DICIT\Config\AbstractConfig')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $config->expects($this->any())
            ->method('doLoad')
            ->will($this->returnValue(array(
                'classes' => array(
                    'service' => array('class' => '\stdClass', 'props' => array('dep' => '@missing-service'))
            ))));

        $container = new Container($config);

        $container->get('service');
    }

    public function testContainerReturnsCorrectParameterValue()
    {
        $config = $this->getMockBuilder('\DICIT\Config\AbstractConfig')
        ->disableOriginalConstructor()
        ->getMockForAbstractClass();

        $config->expects($this->any())
            ->method('doLoad')
            ->will($this->returnValue(array(
                'parameters' => array(
                    'param' => 'value'
                ))));

        $container = new Container($config);

        $this->assertEquals('value', $container->getParameter('param'));
    }

    /**
     * @param string $name
     */
    private function getCyclicDependencies($name, $singletonForFirst = false, $singletonForOther = false)
    {
        $first = array(
            'class' => '\stdClass',
            'singleton' => $singletonForFirst,
            'props' => array(
                'cyclic' => '@' . $name . '-dependency'
            )
        );
        $second = array(
            'class' => '\stdClass',
            'singleton' => $singletonForOther,
            'props' => array(
                'cyclic' => '@' . $name
            )
        );

        return array('classes' => array($name => $first, $name . '-dependency' => $second));
    }

    public function testCyclicDependenciesDoNotOverflowWithOneSingletonInCycle()
    {
        $config = $this->getMockBuilder('\DICIT\Config\AbstractConfig')
            ->disableOriginalConstructor()
            ->setMethods(array('load', 'getData'))
            ->getMockForAbstractClass();

        $first = rand(0, 1);

        $config->expects($this->any())
            ->method('load')
            ->will($this->returnValue($this->getCyclicDependencies('cyclic', $first, ! $first)));

        $activatorFactory = new ActivatorFactory(true);
        $container = new Container($config, $activatorFactory);

        $container->get('cyclic');
    }

    public function testCyclicDependenciesDoNotOverflowWithTwoSingletonsInCycle()
    {
        $config = $this->getMockBuilder('\DICIT\Config\AbstractConfig')
            ->disableOriginalConstructor()
            ->setMethods(array('load', 'getData'))
            ->getMockForAbstractClass();

        $config->expects($this->any())
            ->method('load')
            ->will($this->returnValue($this->getCyclicDependencies('cyclic', true, true)));

        $activatorFactory = new ActivatorFactory(true);
        $container = new Container($config, $activatorFactory);

        $container->get('cyclic');
    }

}

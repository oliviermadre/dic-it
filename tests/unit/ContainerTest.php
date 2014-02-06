<?php

namespace DICIT\Tests;

use DICIT\Container;
class ContainerTest extends \PHPUnit_Framework_TestCase
{

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

        $container = new Container($config);

        $container->get('cyclic');
    }

    public function testCyclicDependenciesDoNotOverflowWithTwoSingletonsInCycle()
    {
        //$this->markTestSkipped('Not implemented yet.');

        $config = $this->getMockBuilder('\DICIT\Config\AbstractConfig')
        ->disableOriginalConstructor()
        ->setMethods(array('load', 'getData'))
        ->getMockForAbstractClass();

        $config->expects($this->any())
            ->method('load')
            ->will($this->returnValue($this->getCyclicDependencies('cyclic', true, true)));

        $container = new Container($config);

        $container->get('cyclic');
    }

}

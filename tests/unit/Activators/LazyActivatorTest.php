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

}
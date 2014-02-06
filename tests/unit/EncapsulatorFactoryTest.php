<?php

namespace DICIT\Tests;

use DICIT\EncapsulatorFactory;
class EncapsulatorFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactoryIsInitializedWithOneEncapsulator()
    {
        $factory = new EncapsulatorFactory();

        $this->assertGreaterThanOrEqual(1, count($factory->getEncapsulators()));
    }

    public function testFactoryReturnsAddedEncapsulators()
    {
        $encapsulator = $this->getMock('\DICIT\Encapsulator');

        $factory = new EncapsulatorFactory();
        $factory->addEncapsulator($encapsulator);

        $this->assertContains($encapsulator, $factory->getEncapsulators());
    }

}

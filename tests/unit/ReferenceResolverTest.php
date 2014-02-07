<?php

namespace DICIT\Tests;

use DICIT\ReferenceResolver;
class ReferenceResolverTest extends \PHPUnit_Framework_TestCase
{

    private $container;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder('\DICIT\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnValue('injected-service'));

        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue('injected-parameter'));
    }

    public function testObjectReferencesAreProperlyResolved()
    {
        $reference = '@service';

        $resolver = new ReferenceResolver($this->container);

        $this->assertEquals('injected-service', $resolver->resolve($reference));
    }

    public function testParameterReferencesAreProperlyResolved()
    {
        $reference = '%parameter';

        $resolver = new ReferenceResolver($this->container);

        $this->assertEquals('injected-parameter', $resolver->resolve($reference));
    }

    public function testValueIsResolvedAsItself()
    {
        $reference = 'some value';

        $resolver = new ReferenceResolver($this->container);

        $this->assertEquals($reference, $resolver->resolve($reference));
    }

    public function testResolveManyResolvesCorrectValues()
    {
        $references = array('@service', '%parameter', 'some value');
        $expected = array('injected-service', 'injected-parameter', 'some value');

        $resolver = new ReferenceResolver($this->container);

        $this->assertEquals($expected, $resolver->resolveMany($references));
    }

}
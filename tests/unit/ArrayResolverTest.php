<?php

namespace DICIT\Tests;

use DICIT\ArrayResolver;

class ArrayResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleResolutionReturnsCorrectValue()
    {
        $data = array('key' => 'value');

        $resolver = new ArrayResolver($data);

        $this->assertEquals('value', $resolver->resolve('key', null));
    }

    public function testSimpleResolutionOfMissingKeyReturnsDefaultValue()
    {
        $data = array();

        $resolver = new ArrayResolver($data);

        $this->assertEquals('default', $resolver->resolve('missing', 'default'));
    }

    public function testArrayResolutionReturnsArrayResolver()
    {
        $data = array('key' => array('sub-key' => 'value'));

        $resolver = new ArrayResolver($data);

        $this->assertInstanceOf('\DICIT\ArrayResolver', $resolver->resolve('key'));
    }

    public function testDottedResolutionReturnsCorrectValue()
    {
        $data = array('key' => array('sub' => array('key' => 'value')));

        $resolver = new ArrayResolver($data);

        $this->assertEquals('value', $resolver->resolve('key.sub.key'));
    }


    public function testDottedKeyResolutionReturnsCorrectValue()
    {
        $data = array('key' => array('sub.key' => 'value'));

        $resolver = new ArrayResolver($data);

        $this->assertEquals('value', $resolver->resolve('key.sub\.key'));
    }


    public function testEscapedDottedKeyResolutionReturnsCorrectValue()
    {
        $data = array('key' => array('sub\.key' => 'value'));

        $resolver = new ArrayResolver($data);

        $this->assertEquals('value', $resolver->resolve("key.sub\\\\\.key"));
    }

    public function testDottedResolutionOfMissingKeyReturnsCorrectValue()
    {
        $data = array('key' => array());

        $resolver = new ArrayResolver($data);

        $this->assertEquals('default', $resolver->resolve('key.sub-key', 'default'));
        $this->assertEquals('other-default', $resolver->resolve('key.other-key', 'other-default'));
    }
}

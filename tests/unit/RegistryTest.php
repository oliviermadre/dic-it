<?php

namespace DICIT\Tests;

use DICIT\Registry;

class RegistryTest extends \PHPUnit_Framework_TestCase
{

    public function testFlushClearsRegistry()
    {
        $registry = new Registry();
        $value = 'value';

        $registry->set('key', $value);
        $registry->flush();

        $this->assertNull($registry->get('key', false));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetThrowsExceptionWithExceptionFlagOn()
    {
        $registry = new Registry();

        $registry->get('unknownKey', true);
    }

    public function testGetReturnsNullWithExceptionFlagOff()
    {
        $registry = new Registry();

        $this->assertNull($registry->get('unknownKey', false));
    }

    public function testGetReturnsSetValue()
    {
        $registry = new Registry();
        $value = 'myValue';

        $registry->set('key', $value);

        $this->assertEquals($value, $registry->get('key'));
    }

}

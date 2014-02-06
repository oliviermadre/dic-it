<?php

namespace DICIT\Tests;

use DICIT\Container;
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    
    private function getCyclicDependencies($name) 
    {
        $first = array(
            'class' => '\stdClass',
            'props' => array(
                'cyclic' => '@cyclic-dependency'        	
            ) 
        );
        $second = array(
            'class' => '\stdClass',
            'props' => array(
                'cyclic' => '@' . $name
            )
        );
        
        return array('classes' => array($name => $first, 'cyclic-dependency' => $second));
    }
    
    public function testCyclicDependenciesDoNotOverflow()
    {
        $this->markTestSkipped('Not implemented yet.');
        
        $config = $this->getMockBuilder('\DICIT\Config\AbstractConfig')
            ->disableOriginalConstructor()
            ->setMethods(array('load', 'getData'))
            ->getMockForAbstractClass();
        
        $config->expects($this->any())
            ->method('load')
            ->will($this->returnValue($this->getCyclicDependencies('cyclic')));
        
        $container = new Container($config);
        
        $container->get('cyclic');
    }
    
}